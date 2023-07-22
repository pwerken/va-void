<?php
declare(strict_types=1);

namespace App\Authentication;

use Authentication\Authenticator\AuthenticationRequiredException as ARE;
use Cake\Routing\Router;

class AuthenticationRequiredException
    extends ARE
{
    public function __construct()
    {
        $uri = Router::url('/auth/login');
        $headers = [ 'WWW-Authenticate' => 'Bearer authorization_uri='.$uri ];

        parent::__construct($headers);
    }
}
