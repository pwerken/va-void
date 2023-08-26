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
use App\Model\Entity\Player;

class SocialAuthComponent
    extends Component
{
    use LocatorAwareTrait;

    protected $_defaultConfig =
        [ 'profileModel' => 'ADmad/SocialAuth.SocialProfiles'
        , 'serviceConfig' =>
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

        $redirectUri = Router::url(
            [ 'controller' => 'Admin'
            , 'action' => 'social'
            ], true) . '/${provider}';
        $this->setConfig('serviceConfig.redirectUri', $redirectUri);

        $this->_factory = new CollectionFactory();
        $this->_factory->register(GitLabProvider::NAME, GitLabProvider::class);

        $this->_profileModel = $this->fetchTable($this->getConfig('profileModel'));
        $this->_playerModel = $this->fetchTable('Players');
    }

    // URL to redirect to, for logging in via the social site
    public function authUrl(string $providerName): string
    {
        return $this->_getProvider($providerName)->makeAuthUrl();
    }

    // after social login, get the access token from the social site
    public function loginCallbackProfile(string $providerName): ?Player
    {
        $request = $this->getController()->getRequest();
        $queryParams = $request->getQueryParams();

        try {
            $provider = $this->_getProvider($providerName);
            $token = $provider->getAccessTokenByRequestParameters($queryParams);
            $identity = $provider->getIdentity($token);

            if(!$identity->id) {
                throw new RuntimeException(
                    "`id` field is empty for the identity returned by `{$providerName}` provider."
                );
            }

            $profile = $this->_getProfile($providerName, $identity);
            return $this->_getUser($profile);
        } catch (SocialConnectException $e) {
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
        return null;
    }

    protected function _getProfile($providerName, $identity)
    {
        $profile = $this->_profileModel
            ->find()
            ->where(
                ['provider' => $providerName
                ,'identifier' => $identity->id
                ])
            ->first();

        if($profile === null) {
            $profile = $this->_profileModel->newEntity([]);
        }

        $data = ['provider' => $providerName];
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

        return $this->_profileModel->patchEntity($profile, $data);
    }

    protected function _getProvider(string $providerName)
    {
        return $this->_getService()->getProvider($providerName);
    }

    protected function _getService(): Service
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
        return $this->_service;
    }

    protected function _getUser($profile)
    {
        $user = null;
        $id = $profile->get('user_id');
        $email = $profile->get('email');

        if(!$id and $email) {
            // New login, lookup player by known email.
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
        if(!$id and $email) {
            // Fallback, is there another social profile with the same email?
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

        if($id) {
            $user = $this->_playerModel->find()->where(['id' => $id])->first();
            $profile->set('user_id', $user->get('id'));
        } else {
            // No luck, return a non-existant player without plin
            $unknown = ['first_name' => 'Onbekende', 'last_name' => 'Speler'];
            $user = $this->_playerModel->newEntity($unknown);
        }

        if($profile->isDirty()) {
            $this->_profileModel->save($profile);
        }

        return $user;
    }

    //TODO? needed when getting 'code' passed in by the front-end app
    public function setStateless()
    {
        $providers = $this->getConfig('serviceConfig.provider');
        foreach($providers as $key => $provider) {
            $this->setConfig("serviceConfig.provider.$key.stateless", true);
        }
    }
}
