<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Utility\Security;
use Firebase\JWT\JWT;

use App\Error\LoginFailedException;

class AuthController
    extends AppController
{

    public function initialize(): void
    {
        parent::initialize();

        $this->Authentication->allowUnauthenticated([
            'login',
            'logout',
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
        $this->set(
            [ '_serialize' =>
                [ 'class' => 'Auth'
                , 'token' => JWT::encode(
                    [ 'sub' => $user['id']
                    , 'exp' =>  time() + 60*60*24*7
                    , 'name' => $user['full_name']
                    , 'role' => $user['role']
                    ], Security::getSalt(), 'HS256')
                , 'player' => '/players/'.$user['id']
                ]
            ]);
    }

    // GET /auth/logout
    public function logout(): void
    {
        $this->Authentication->logout();
    }
}
