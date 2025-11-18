<?php
declare(strict_types=1);

namespace App\Controller\Component;

use App\Authentication\Social\CollectionFactory;
use App\Error\Exception\LoginFailedException;
use App\Error\Exception\UnlinkedAccountException;
use App\Model\Entity\Player;
use App\Model\Table\PlayersTable;
use App\Model\Table\SocialProfilesTable;
use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;
use Cake\Core\Configure;
use Cake\Http\Client;
use Cake\Http\Session;
use Cake\Log\Log;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\Routing\Router;
use Laminas\Diactoros\RequestFactory;
use Laminas\Diactoros\StreamFactory;
use SocialConnect\Auth\Service;
use SocialConnect\Common\Entity\User;
use SocialConnect\Common\Exception as SocialConnectException;
use SocialConnect\Common\HttpStack;
use SocialConnect\OAuth2\AbstractProvider as OAuth2Provider;
use SocialConnect\OpenIDConnect\AbstractProvider as OpenIDConnectProvider;
use SocialConnect\OpenIDConnect\AccessToken as OpenIDConnectToken;
use SocialConnect\Provider\AbstractBaseProvider as BaseProvider;
use SocialConnect\Provider\AccessTokenInterface as AccessToken;
use SocialConnect\Provider\Exception\InvalidResponse;
use SocialConnect\Provider\Session\SessionInterface;

/**
 * @property \App\Controller\Component\MailerComponent $Mailer;
 */
class SocialAuthComponent extends Component
{
    use LocatorAwareTrait;

    protected array $components = ['Mailer'];

    protected array $_defaultConfig = [
        'serviceConfig' => [
            'provider' => [
                'discord' => [ 'scope' => [ 'identify', 'email' ] ],
                'facebook' => [ 'scope' => [ 'email' ] ],
                'google' => [
                    'scope' => [
                        'https://www.googleapis.com/auth/userinfo.email',
                        'https://www.googleapis.com/auth/userinfo.profile',
                    ],
                    'options' => [ 'identity.fields' => [ 'email' ] ],
                ],
                'gitlab' => [ 'scope' => [ 'read_user' ] ],
            ],
            'logErrors' => 'true',
        ],
    ];

    protected CollectionFactory $_factory;

    protected PlayersTable $_playerModel;

    protected SocialProfilesTable $_profileModel;

    protected ?Service $_service = null;

    public function __construct(ComponentRegistry $registry)
    {
        parent::__construct($registry);

        # merge in config/app_local.php
        $providerConfig = Configure::read('SocialAuth', []);
        $this->setConfig('serviceConfig.provider', $providerConfig);
        $this->setConfig('serviceConfig.redirectUri', 'CALLBACK');

        $this->_factory = new CollectionFactory();

        $this->_profileModel = $this->fetchTable('SocialProfiles');
        $this->_playerModel = $this->fetchTable('Players');
    }

    /**
     * URL to redirect to, for logging in via the social site
     */
    public function authUrl(string $provider): string
    {
        return $this->getProvider($provider)->makeAuthUrl();
    }

    /**
     * After social login, this get's called by the callback redirect
     */
    public function loginCallback(string $provider): Player
    {
        $query = $this->getController()->getRequest()->getQueryParams();

        try {
            $token = $this->getProvider($provider)
                          ->getAccessTokenByRequestParameters($query);

            return $this->accountFromToken($provider, $token);
        } catch (SocialConnectException $e) {
            $this->_logException($e);
            throw new LoginFailedException("Login via `{$provider}` failed");
        }
    }

    /**
     * Front-end did the social login and caught the callback.
     * It then passes the received 'code' as param here to login the player.
     */
    public function loginWithCode(string $provider, string $code, string $callbackUri): Player
    {
        // code param should already by be set, but lets not assume
        $request = $this->getController()->getRequest();
        $request = $request->withParam('code', $code);
        $this->getController()->setRequest($request);

        // If the client sent a PKCE code_verifier, store it in the session
        // so the library can include it in the token exchange request.
        $codeVerifier = $this->getController()->getRequest()->getQuery('code_verifier');
        if ($codeVerifier) {
            $session = $this->getController()->getRequest()->getSession();
            if (!$session->started()) {
                $session->start();
            }
            $session->write('code_verifier', $codeVerifier);
        }

        // skip the state param check, should be handled by front-end
        $this->setConfig("serviceConfig.provider.$provider.options.stateless", true);
        $this->setConfig('serviceConfig.redirectUri', $callbackUri);

        return $this->loginCallback($provider);
    }

    /**
     * Resolve a player from a provider access token.
     * For OpenIDConnect tokens (which carry a JWT), the identity is extracted
     * directly from the JWT payload. For plain OAuth2 tokens the provider's
     * userinfo endpoint is called instead.
     */
    public function accountFromToken(string $provider, AccessToken $token): Player
    {
        try {
            $providerInstance = $this->getProvider($provider);

            if (
                $providerInstance instanceof OpenIDConnectProvider
                && $token instanceof OpenIDConnectToken
                && $token->getJwt()
            ) {
                $identity = $providerInstance->extractIdentity($token);
            } else {
                $identity = $providerInstance->getIdentity($token);
            }

            if (!$identity->id) {
                throw new SocialConnectException("Provider `{$provider}` returned identity with empty `id` field");
            }

            return $this->_getUser($provider, $identity);
        } catch (SocialConnectException $e) {
            $this->_logException($e);
            throw new LoginFailedException("Login via `{$provider}` failed", null, $e);
        }
    }

    /**
     * Return list of OAuth2 provider's that are supported and configured.
     */
    public function getOAuth2Providers(): array
    {
        $providers = [];
        foreach ($this->_factory->getProviders() as $name => $provider) {
            if (!is_a($provider, OAuth2Provider::class, true)) {
                continue;
            }
            if (empty($this->getConfig('serviceConfig.provider.' . $name . '.applicationId'))) {
                continue;
            }
            $providers[] = $name;
        }

        return $providers;
    }

    /**
     * Return list of OpenIDConnect provider's that are supported.
     */
    public function getOpenIDConnectProviders(): array
    {
        $providers = [];
        foreach ($this->_factory->getProviders() as $name => $provider) {
            if (!is_a($provider, OpenIDConnectProvider::class, true)) {
                continue;
            }
            $providers[] = $name;
        }

        return $providers;
    }

    public function setCallbackUri(array|string $url): void
    {
        $callbackUri = Router::url($url, true);
        $this->setConfig('serviceConfig.redirectUri', $callbackUri);
    }

    public function getProvider(string $provider): BaseProvider
    {
        if (is_null($this->_service)) {
            $session = $this->getController()->getRequest()->getSession();
            if (!$session->started()) {
                $session->start();
            }

            $httpStack = new HttpStack(
                new Client(),
                new RequestFactory(),
                new StreamFactory(),
            );

            $this->_service = new Service(
                $httpStack,
                $this->_wrapSession($session),
                $this->getConfig('serviceConfig'),
                $this->_factory,
            );
        }

        return $this->_service->getProvider($provider);
    }

    protected function _wrapSession(Session $session): SessionInterface
    {
        return new class ($session) implements SessionInterface
        {
            private Session $session;

            public function __construct(Session $session)
            {
                $this->session = $session;
            }

            // phpcs:ignore
            public function get($key): mixed
            {
                return $this->session->read($key);
            }

            // phpcs:ignore
            public function set($key, $value): void
            {
                $this->session->write($key, $value);
            }

            // phpcs:ignore
            public function delete($key): void
            {
                $this->session->delete($key);
            }
        };
    }

    protected function _getUser(string $provider, User $identity): Player
    {
        // first look for SocialProfile in DB
        /** @var \App\Model\Entity\SocialProfile $profile */
        $profile = $this->_profileModel
            ->find()
            ->where([
                'provider' => $provider,
                'identifier' => $identity->id,
                ])
            ->first();

        if (is_null($profile)) {
            $profile = $this->_profileModel->newEntity([]);
        }

        // update $profile with data from $identity
        $data = ['provider' => $provider];
        foreach (get_object_vars($identity) as $key => $value) {
            switch ($key) {
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
        $id = $profile->get('user_id');
        $email = $profile->get('email');

        if (!$email && !$id) {
            // Apple may omit email on subsequent sign-ins. If we already have
            // a saved profile for this provider+identifier, use its stored email.
            $existing = $this->_profileModel
                ->find()
                ->where([
                    'provider' => $provider,
                    'identifier' => $identity->id,
                ])
                ->first();
            if ($existing) {
                $email = $existing->get('email');
                $id = $existing->get('user_id');
                $profile->set('email', $email);
                $profile->set('user_id', $id);
            }
        }

        if (!$email && !$id) {
            throw new SocialConnectException('No email provided by social provider');
        }

        if (!$id) {
            // new login, look for player based on known email
            $result = $this->_playerModel
                ->find()
                ->select('plin', true)
                ->where(['email' => $email])
                ->disableHydration()
                ->first();
            if ($result) {
                $id = $result['plin'];
            }
        }
        if (!$id) {
            // fallback, is there another social profile with the same email?
            $result = $this->_profileModel
                ->find()
                ->select('user_id', true)
                ->where(['email' => $profile->get('email'), 'user_id IS NOT NULL'])
                ->disableHydration()
                ->first();
            if ($result) {
                $id = $result['user_id'];
            }
        }
        $profile->set('user_id', $id);
        $this->Mailer->socialLogin($profile);

        if ($profile->isDirty()) {
            if (!$this->_profileModel->save($profile)) {
                throw new SocialConnectException('Failed to save profile');
            }
        }

        if (!$id) {
            throw new UnlinkedAccountException('Email has no associated plin. Site admin notified. Expect an email.');
        }

        return $this->_playerModel->get($id);
    }

    protected function _logException(SocialConnectException $e): void
    {
        if (!$this->getConfig('serviceConfig.logErrors')) {
            return;
        }

        $request = $this->getController()->getRequest();

        $message = sprintf('[%s] %s', get_class($e), $e->getMessage());
        $message .= "\n Request URL: " . $request->getRequestTarget();

        $referer = $request->getHeaderLine('Referer');
        if ($referer) {
            $message .= "\nReferer URL: " . $referer;
        }

        if ($e instanceof InvalidResponse) {
            $response = $e->getResponse();
            $message .= "\nProvided Response: ";
            $message .= ($response ? (string)$response->getBody() : 'n/a');
        }

        $message .= "\nStack Trace:\n" . $e->getTraceAsString();

        Log::error($message);
    }
}
