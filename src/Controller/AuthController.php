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
        if (empty($provider)) {
            $this->socialListing();
        } else {
            $this->socialLogin($provider);
        }
    }

    protected function socialListing(): void
    {
        $result = [];
        $result['class'] = 'List';
        $result['url'] = Router::url([
            'controller' => 'Auth',
            'action' => 'social',
        ]);
        $result['list'] = [];

        foreach ($this->SocialAuth->getProviders() as $provider) {
            $auth = $this->SocialAuth->authUrl($provider);
            $auth = preg_replace('/(\&state)=[^&]*/', '\1=STATE', $auth);

            $item = [];
            $item['class'] = 'SocialLogin';
            $item['name'] = $provider;
            $item['url'] = Router::url([
                'controller' => 'Auth',
                'action' => 'social',
                $provider,
                '?' => ['code' => 'CODE', 'redirect_uri' => 'CALLBACK'],
            ]);
            $item['authUri'] = $auth;

            $result['list'][] = $item;
        }

        $this->set('_serialize', $result);
    }

    protected function socialLogin(string $provider): void
    {
        $providers = $this->SocialAuth->getProviders();
        if (!in_array($provider, $providers)) {
            throw new NotFoundException();
        }

        $token = $this->request->getQuery('token');
        if ($token) {
            $obj = new AccessToken(['access_token' => $token]);
            $user = $this->SocialAuth->accountFromToken($provider, $obj);
            $this->set('_serialize', $this->jwt($user));

            return;
        }

        $code = $this->request->getQuery('code');
        if (!$code) {
            throw new BadRequestException('Missing `code` query parameter');
        }

        $redirectUri = $this->request->getQuery('redirect_uri');
        if (!$redirectUri) {
            throw new BadRequestException('Missing `redirect_uri` query parameter');
        }

        $user = $this->SocialAuth->loginWithCode($provider, $code, $redirectUri);
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
                    'sub' => $user['id'],
                    'exp' => time() + 60 * 60 * 24 * 7,
                    'name' => $user['full_name'],
                    'role' => $user['role'],
                ], Security::getSalt(), 'HS256'),
            'player' => '/players/' . $user['id'],
            'plin' => $user['id'],
        ];
    }
}
