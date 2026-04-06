<?php
declare(strict_types=1);

namespace App\Policy\Controller;

class AuthControllerPolicy extends ControllerPolicy
{
    /**
     * GET /auth/login
     * PUT /auth/login
     * POST /auth/login
     */
    public function login(): bool
    {
        return true;
    }

    /**
     * GET /auth/social
     * GET /auth/social/{provider}
     */
    public function social(): bool
    {
        return true;
    }

    /**
     * GET /auth/OAuth2
     * GET /auth/OAuth2/{provider}
     */
    public function oauth2(): bool
    {
        return true;
    }

    /**
     * GET /auth/OpenIDConnect
     * GET /auth/OpenIDConnect/{provider}
     */
    public function openIDConnect(): bool
    {
        return true;
    }
}
