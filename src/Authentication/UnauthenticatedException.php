<?php
declare(strict_types=1);

namespace App\Authentication;

use Authentication\Authenticator\UnauthenticatedException as UE;
use Cake\Routing\Router;

class UnauthenticatedException
    extends UE
{
    public function __construct()
    {
        parent::__construct();

        $uri = Router::url('/auth/login');
        $this->setHeader('WWW-Authenticate', 'Bearer authorization_uri='.$uri);
    }
}
