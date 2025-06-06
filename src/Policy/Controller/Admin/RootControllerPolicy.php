<?php
declare(strict_types=1);

namespace App\Policy\Controller\Admin;

use App\Policy\Controller\ControllerPolicy;

class RootControllerPolicy extends ControllerPolicy
{
    public function index(): bool
    {
        return true;
    }
}
