<?php
declare(strict_types=1);

namespace App\Routing\Middleware;

use ADmad\SocialAuth\Middleware\SocialAuthMiddleware as ADmadMiddleware;
use Cake\Core\Configure;
use Cake\Utility\Hash;
use SocialConnect\Auth\CollectionFactory;

use App\Authentication\Social\GitLabProvider;

class SocialAuthMiddleware
    extends ADmadMiddleware
{
    public function __construct()
    {
        $factory = new CollectionFactory();
        $factory->register(GitLabProvider::NAME, GitLabProvider::class);

        $default =
            [ 'loginUrl' => '/admin'
            , 'userEntity' => true
            , 'userModel' => 'Players'
            , 'collectionFactory' => $factory
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
            , 'logErrors' => true
            ];

        $config = Configure::read('SocialAuth', []);
        $config = [ 'serviceConfig' => [ 'provider' => $config ] ];

        parent::__construct(Hash::merge($default, $config));
    }
}
