<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\NotFoundException;
use Cake\Routing\Router;
use Cake\Utility\Security;
use Firebase\JWT\JWT;

use App\Error\LoginFailedException;
use App\Model\Entity\Player;

class AuthController
    extends AppController
{
    public function initialize(): void
    {
        parent::initialize();

        $this->loadComponent('SocialAuth');

        $this->Authentication->allowUnauthenticated([
            'login',
            'logout',
            'socialListing',
            'socialLogin',
        ]);
    }

    // GET  /auth/login (refresh JWT)
    // PUT  /auth/login (new JWT)
    // POST /auth/login (new JWT)
    public function login(): void
    {
        $result = $this->Authentication->getResult();
        if (!$result->isValid()) {
            throw new LoginFailedException('Invalid username or password');
        }

        $user = $result->getData();
        $this->set('_serialize', $this->_jwt($user));
    }

    // GET /auth/logout
    public function logout(): void
    {
        $this->Authentication->logout();
    }

    // GET  /auth/social
    public function socialListing(): void
    {
        $result = [];
        $result['class'] = 'List';
        $result['url'] = Router::url(
            [ 'controller' => 'Auth'
            , 'action' => 'socialListing'
            ]);
        $result['list'] = [];

        foreach($this->SocialAuth->getProviders() as $provider)
        {
            $result['list'][] = $this->_social($provider);
        }

        $this->set('_serialize', $result);
    }

    // GET  /auth/social/{provider}?code=...&redirect_uri=...
    public function socialLogin(string $providerName): void
    {
        $providers = $this->SocialAuth->getProviders();
        if(!in_array($providerName, $providers)) {
            throw new NotFoundException();
        }

        $code = $this->request->getQuery('code');
        if(!$code) {
            throw new BadRequestException('Missing "code" query parameter');
        }
        $redirectUri = $this->request->getQuery('redirect_uri');
        if(!$redirectUri) {
            throw new BadRequestException('Missing "redirect_uri" query parameter');
        }

        $user = $this->SocialAuth->loginCode($providerName, $code, $redirectUri);
        if(!$user->get('id')) {
            throw new LoginFailedException('Login has no associated plin');
        }
        $this->set('_serialize', $this->_jwt($user));
    }

    protected function _jwt(Player|array $user): array
    {
        if($user instanceOf Player) {
            $user = $user->toArray();
        }

        return  [ 'class' => 'Auth'
                , 'token' => JWT::encode(
                    [ 'sub' => $user['id']
                    , 'exp' =>  time() + 60*60*24*7
                    , 'name' => $user['full_name']
                    , 'role' => $user['role']
                    ], Security::getSalt(), 'HS256')
                , 'player' => '/players/'.$user['id']
                ];
    }

    protected function _social(string $provider): array
    {
        $auth = $this->SocialAuth->authUrl($provider);
        $auth = preg_replace('/(\&state)=[^&]*/', '\1=STATE', $auth);

        $result = [];
        $result['class'] = 'SocialLogin';
        $result['name'] = $provider;
        $result['url'] = Router::url(
            [ 'controller' => 'Auth'
            , 'action' => 'socialLogin'
            , $provider
            , '?' => ['code' => 'CODE', 'redirect_uri' => 'CALLBACK']
            ]);
        $result['authUri'] = $auth;

        return $result;
    }
}
