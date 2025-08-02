<?php
declare(strict_types=1);

namespace App\Authentication;

use Authentication\AuthenticationServiceInterface;
use Authentication\AuthenticationServiceProviderInterface;
use Psr\Http\Message\ServerRequestInterface;

class AuthenticationServiceProvider implements AuthenticationServiceProviderInterface
{
    public function getAuthenticationService(ServerRequestInterface $request): AuthenticationServiceInterface
    {
        $params = $request->getAttribute('params');
        $isAdmin = isset($params['prefix']) && $params['prefix'] === 'Admin';

        return new AuthenticationService($isAdmin);
    }
}
