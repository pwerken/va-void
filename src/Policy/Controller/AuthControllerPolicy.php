<?php
declare(strict_types=1);

namespace App\Policy\Controller;

class AuthControllerPolicy
    extends AppControllerPolicy
{
    public function login()
    {
        return true;
    }

    public function logout()
    {
        return true;
    }
}
