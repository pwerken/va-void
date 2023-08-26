<?php
declare(strict_types=1);

namespace App\Middleware;

use ADmad\SocialAuth\Middleware\SocialAuthMiddleware as ADmadMiddleware;
use Cake\Core\Configure;
use Cake\Datasource\EntityInterface;
use Cake\Http\Session;
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

    protected function _getUserEntity(EntityInterface $profile, Session $session): EntityInterface
    {
        $email = $profile->email;
        if(!$email) {
            return $this->unknownPlayer();
        }

        # New login, try to find Player based on email.
        $player = $this->_userModel
                        ->find()
                        ->where(['email' => $email])
                        ->first();
        if($player) {
            return $player;
        }

        # Fallback, is there another social profile with the same email ?
        $result = $this->_profileModel
                        ->find()
                        ->select('user_id', true)
                        ->where(['email' => $email, 'user_id IS NOT NULL'])
                        ->disableHydration()
                        ->first();
        if($result) {
            return $players->get($result['user_id']);
        }

        # unknown email / unlinked social profile login
        return $this->unknownPlayer();
    }

    private function unknownPlayer()
    {
        $unknown = ['first_name' => 'Onbekende', 'last_name' => 'Speler'];
        return $this->_userModel->newEntity($unknown);
    }
}
