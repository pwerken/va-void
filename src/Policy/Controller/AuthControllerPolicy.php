<?php
declare(strict_types=1);

namespace App\Policy\Controller;

class AuthControllerPolicy extends ControllerPolicy
{
    // GET  /auth/login
    // PUT  /auth/login
    // POST /auth/login

    public function login(): bool
    {
        return true;
    }

    // GET /auth/logout

    public function logout(): bool
    {
        return true;
    }

    // GET /auth/social

    public function socialListing(): bool
    {
        return true;
    }

    // PUT  /auth/social/{provider}
    // POST /auth/social/{provider}
    public function socialLogin(): bool
    {
        return true;
    }
}
