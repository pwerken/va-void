<?php
declare(strict_types=1);

namespace App\Policy\Controller;

class AuthControllerPolicy
    extends AppControllerPolicy
{
    public function login(): bool
    {
        return true;
    }

    public function logout(): bool
    {
        return true;
    }
}
