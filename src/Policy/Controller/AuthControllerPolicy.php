<?php
declare(strict_types=1);

namespace App\Policy\Controller;

class AuthControllerPolicy
    extends AppControllerPolicy
{
    // GET /auth/login
    // PUT /auth/login
    public function login(): bool
    {
        return true;
    }

    // GET /auth/logout
    public function logout(): bool
    {
        return true;
    }
}
