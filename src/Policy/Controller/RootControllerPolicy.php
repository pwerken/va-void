<?php
declare(strict_types=1);

namespace App\Policy\Controller;

class RootControllerPolicy extends ControllerPolicy
{
    // GET /
    public function index(): bool
    {
        return true;
    }

    // OPTIONS /*
    public function cors(): bool
    {
        return true;
    }
}
