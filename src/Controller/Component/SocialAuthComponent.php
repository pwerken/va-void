<?php
declare(strict_types=1);

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;
use Cake\Core\Configure;
use Cake\Http\Client;
use Cake\Http\Response;
use Cake\Log\Log;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\Routing\Router;
use Laminas\Diactoros\RequestFactory;
use Laminas\Diactoros\StreamFactory;
use SocialConnect\Auth\CollectionFactory;
use SocialConnect\Auth\Service;
use SocialConnect\Common\Exception as SocialConnectException;
use SocialConnect\Common\HttpStack;
use SocialConnect\Provider\InvalidResponse;
use SocialConnect\Provider\Session\Session as SocialConnectSession;

use App\Authentication\Social\GitLabProvider;
use App\Error\LoginFailedException;
use App\Model\Entity\Player;

class SocialAuthComponent
    extends Component
{
    use LocatorAwareTrait;

    protected $components = ['Mailer'];

    protected $_defaultConfig =
        [ 'serviceConfig' =>
            [ 'provider' =>
                [ 'discord' =>
                    [ 'scope' => [ 'identify', 'email' ]
                    ]
                , 'facebook' =>
                    [ 'scope' => [ 'email' ]
                    ]
                , 'google' =>
                    [ 'scope' => [ 'https://www.googleapis.com/auth/userinfo.email', 'https://www.googleapis.com/auth/userinfo.profile' ]
                    , 'options' => [ 'identity.fields' => [ 'email' ] ]
                    ]
                , 'gitlab' =>
                    [ 'scope' => [ 'read_user' ]
                    ]
                ]
            ]
        , 'logErrors' => 'true'
        ];

    protected $_factory = null;

    protected $_playerModel = null;

    protected $_profileModel = null;

    protected $_service = null;

    public function __construct(ComponentRegistry $registry)
    {
        parent::__construct($registry);

        # merge in config/app_local.php
        $providerConfig = Configure::read('SocialAuth', []);
        $this->setConfig('serviceConfig.provider', $providerConfig);
        $this->setConfig('serviceConfig.redirectUri', 'CALLBACK');

        $this->_factory = new CollectionFactory();
        $this->_factory->register(GitLabProvider::NAME, GitLabProvider::class);

        $this->_profileModel = $this->fetchTable('SocialProfiles');
        $this->_playerModel = $this->fetchTable('Players');
    }

    // URL to redirect to, for logging in via the social site
    public function authUrl(string $provider): string
    {
        return $this->_getProvider($provider)->makeAuthUrl();
    }

    // After social login, this get's called by the callback redirect
    public function loginCallback(string $provider): Player
    {
        $query = $this->getController()->getRequest()->getQueryParams();

        try {
            $token = $this->_getProvider($provider)
                          ->getAccessTokenByRequestParameters($query);
            return $this->accountFromToken($provider, $token);
        }
        catch (SocialConnectException $e) {
            $this->_logException($e);

            throw new LoginFailedException('Login via '.$provider.' failed');
        }
    }

    // Front-end did the social login and caught the callback.
    // It then passes the received 'code' as param here to login the player.
    public function loginWithCode(string $provider, string $code, string $callbackUri): Player
    {
        // code param should already by be set, but lets not assume
        $request = $this->getController()->getRequest();
        $request = $request->withParam('code', $code);
        $this->getController()->setRequest($request);

        // skip the state param check, should be handled by front-end
        $this->setConfig("serviceConfig.provider.$provider.options.stateless", true);
        $this->setConfig('serviceConfig.redirectUri', $callbackUri);

        return $this->loginCallback($provider);
    }

    // Client did all provider authentication by it self.
    // We use the provided 'token' to get info from the social provider.
    public function accountFromToken($provider, $token): Player
    {
        $identity = $this->_getProvider($provider)->getIdentity($token);
        if(!$identity->id) {
            throw new SocialConnectException(
                "`id` field is empty for the identity returned by `{$provider}` provider."
            );
        }

        return $this->_getUser($provider, $identity);
    }

    // Return list of provider's that are supported and configured.
    public function getProviders(): array
    {
        $result = [];
        $providers = $this->getConfig('serviceConfig.provider');
        foreach($providers as $key => $value)
        {
            if(!empty($value['applicationId'])) {
                $result[] = $key;
            }
        }
        return $result;
    }

    public function setCallbackUri(array|string $url)
    {
        $callbackUri = Router::url($url, true);
        $this->setConfig('serviceConfig.redirectUri', $callbackUri);
    }

    protected function _getProvider(string $provider)
    {
        if(is_null($this->_service))
        {
            $this->getController()->getRequest()->getSession()->start();

            $httpStack = new HttpStack(
                new Client(),
                new RequestFactory(),
                new StreamFactory()
            );

            $this->_service = new Service(
                $httpStack,
                new SocialConnectSession(),
                $this->getConfig('serviceConfig'),
                $this->_factory
            );
        }

        return $this->_service->getProvider($provider);
    }

    protected function _getUser($provider, $identity): Player
    {
        // first look for SocialProfile in DB
        $profile = $this->_profileModel
            ->find()
            ->where(
                ['provider' => $provider
                ,'identifier' => $identity->id
                ])
            ->first();

        if($profile === null) {
            $profile = $this->_profileModel->newEntity([]);
        }

        // update $profile with data from $identity
        $data = ['provider' => $provider];
        foreach(get_object_vars($identity) as $key => $value) {
            switch($key) {
                case 'id':
                    $data['identifier'] = $value;
                    break;
                case 'fullname':
                    $data['full_name'] = $value;
                    break;
                default:
                    $data[$key] = $value;
                    break;
            }
        }
        $profile = $this->_profileModel->patchEntity($profile, $data);

        // then try to find the related player
        $user = null;
        $id = $profile->get('user_id');
        $email = $profile->get('email');

        if(!$email) {
            throw new SocialConnectException('Missing e-mail adres');
        }

        if(!$id) {
            // new login, look for player based on known email
            $result = $this->_playerModel
                ->find()
                ->select('id', true)
                ->where(['email' => $email])
                ->disableHydration()
                ->first();
            if($result) {
                $id = $result['id'];
            }
        }
        if(!$id) {
            // fallback, is there another social profile with the same email?
            $result = $this->_profileModel
                ->find()
                ->select('user_id', true)
                ->where(['email' => $profile->email, 'user_id IS NOT NULL'])
                ->disableHydration()
                ->first();
            if($result) {
                $id = $result['user_id'];
            }
        }
        $profile->set('user_id', $id);
        $this->Mailer->socialLogin($profile);

        if($profile->isDirty()) {
            if(!$this->_profileModel->save($profile)) {
                throw new SocialConnectException('Failed to save profile');
            }
        }

        if(!$id) {
            throw new LoginFailedException('Email has no associated plin. Site admin notified. Expect an email.');
        }

        return $this->_playerModel->get($id);
    }

    protected function _logException($e): void
    {
        if(!$this->getConfig('logErrors')) {
            return;
        }

        $request = $this->getController()->getRequest();

        $message = sprintf('[%s] %s', get_class($e), $e->getMessage());
        $message .= "\n Request URL: " . $request->getRequestTarget();

        $referer = $request->getHeaderLine('Referer');
        if($referer) {
            $message .= "\nReferer URL: " . $referer;
        }

        if ($e instanceof InvalidResponse) {
            $response = $e->getResponse();
            $messgae .= "\nProvided Response: ";
            $message .= ($response ? (string)$response->getBody() : 'n/a');
        }

        $message .= "\nStack Trace:\n" . $e->getTraceAsString() . "\n\n";

        Log::error($message);
    }
}
