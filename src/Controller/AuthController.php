<?php
declare(strict_types=1);

namespace App\Controller;

use App\Error\Exception\LoginFailedException;
use App\Model\Entity\Player;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\NotFoundException;
use Cake\Routing\Router;
use Cake\Utility\Security;
use Firebase\JWT\JWT;
use SocialConnect\OAuth2\AccessToken;
use SocialConnect\Provider\Exception\InvalidAccessToken;

/**
 * @property \App\Controller\Component\SocialAuthComponent $SocialAuth;
 */
class AuthController extends Controller
{
    public function initialize(): void
    {
        parent::initialize();

        $this->loadComponent('SocialAuth');

        $this->Authentication->allowUnauthenticated([
            'login',
            'social',
            'oauth2',
            'openIDConnect',
        ]);
    }

    /**
     * GET /auth/login (refresh JWT)
     * PUT /auth/login (new JWT)
     * POST /auth/login (new JWT)
     */
    public function login(): void
    {
        $result = $this->Authentication->getResult();
        if (!$result->isValid()) {
            throw new LoginFailedException('Invalid username or password');
        }

        $user = $result->getData();
        $this->set('_serialize', $this->jwt($user));
    }

    /**
     * GET /auth/social
     * GET /auth/social/{provider}?code=...&redirect_uri=...
     * GET /auth/social/{provider}?token=...
     */
    public function social(?string $provider = null): void
    {
        $this->oauth2($provider);
    }

    /**
     * GET /auth/OAuth2
     * GET /auth/OAuth2/{provider}?code=...&redirect_uri=...
     * GET /auth/OAuth2/{provider}?token=...
     */
    public function oauth2(?string $provider = null): void
    {
        if (empty($provider)) {
            $this->oauth2Listing();
        } else {
            $this->oauth2Login($provider);
        }
    }

    protected function oauth2Listing(): void
    {
        $result = [];
        $result['class'] = 'List';
        $result['url'] = Router::url([
            'controller' => 'Auth',
            'action' => 'oauth2',
        ]);
        $result['list'] = [];

        foreach ($this->SocialAuth->getOauth2Providers() as $name) {
            $auth = $this->SocialAuth->authUrl($name);
            $auth = preg_replace('/(\&state)=[^&]*/', '\1=STATE', $auth);

            $item = [];
            $item['class'] = 'OAuth2';
            $item['name'] = $name;
            $item['loginRedirect'] = $auth;
            $item['urlLoginCode'] = Router::url([
                'controller' => 'Auth',
                'action' => 'oauth2',
                $name,
                '?' => ['code' => 'CODE', 'redirect_uri' => 'CALLBACK'],
            ]);
            $item['urlAccessToken'] = Router::url([
                'controller' => 'Auth',
                'action' => 'oauth2',
                $name,
                '?' => ['token' => 'TOKEN'],
            ]);

            $result['list'][] = $item;
        }

        $this->set('_serialize', $result);
    }

    protected function oauth2Login(string $name): void
    {
        $providers = $this->SocialAuth->getOAuth2Providers();
        if (!in_array($name, $providers)) {
            throw new NotFoundException();
        }

        $token = $this->getRequest()->getQuery('token');
        if ($token) {
            $access = new AccessToken(['access_token' => $token]);
            $user = $this->SocialAuth->accountFromToken($name, $access);
            $this->set('_serialize', $this->jwt($user));

            return;
        }

        $code = $this->getRequest()->getQuery('code');
        if (!$code) {
            throw new BadRequestException('Missing `code` query parameter');
        }

        $redirectUri = $this->getRequest()->getQuery('redirect_uri');
        if (!$redirectUri) {
            throw new BadRequestException('Missing `redirect_uri` query parameter');
        }

        $user = $this->SocialAuth->loginWithCode($name, $code, $redirectUri);
        $this->set('_serialize', $this->jwt($user));
    }

    /**
     * GET /auth/OpenIDConnect
     * GET /auth/OpenIDConnect/{provider}?token=...
     */
    public function openIDConnect(?string $provider = null): void
    {
        if (is_null($provider)) {
            $this->openIDConnectListing();
        } else {
            $this->openIDConnectLogin($provider);
        }
    }

    protected function openIDConnectListing(): void
    {
        $result = [];
        $result['class'] = 'List';
        $result['url'] = Router::url([
            'controller' => 'Auth',
            'action' => 'openIDConnect',
        ]);
        $result['list'] = [];
        foreach ($this->SocialAuth->getOpenIDConnectProviders() as $name) {
            $item = [];
            $item['class'] = 'OpenIDConnect';
            $item['name'] = $name;
            $item['url'] = Router::url([
                'controller' => 'Auth',
                'action' => 'openIDConnect',
                $name,
                '?' => ['token' => 'TOKEN'],
            ]);

            $result['list'][] = $item;
        }
        $this->set('_serialize', $result);
    }

    protected function openIDConnectLogin(string $name): void
    {
        $providers = $this->SocialAuth->getOpenIDConnectProviders();
        if (!in_array($name, $providers)) {
            throw new NotFoundException();
        }

        $token = $this->getRequest()->getQuery('token');
        if (!$token) {
            throw new BadRequestException('Missing `token` query parameter');
        }

        try {
            $provider = $this->SocialAuth->getProvider($name);
            $access = $provider->createAccessToken(['id_token' => $token]);
        } catch (InvalidAccessToken $e) {
            throw new LoginFailedException($e->getMessage(), null, $e);
        }

        $user = $this->SocialAuth->accountFromToken($name, $access);
        $this->set('_serialize', $this->jwt($user));
    }

    protected function jwt(Player|array $user): array
    {
        if ($user instanceof Player) {
            $user = $user->toArray();
        }

        return [
            'class' => 'Auth',
            'token' => JWT::encode([
                    'sub' => $user['plin'],
                    'exp' => time() + 60 * 60 * 24 * 7,
                    'name' => $user['name'],
                    'role' => $user['role'],
                ], Security::getSalt(), 'HS256'),
            'player' => '/players/' . $user['plin'],
            'plin' => $user['plin'],
        ];
    }
}
