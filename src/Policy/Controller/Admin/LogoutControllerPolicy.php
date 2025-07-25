<?php
declare(strict_types=1);

namespace App\Policy\Controller\Admin;

use App\Policy\Controller\ControllerPolicy;

class LogoutControllerPolicy extends ControllerPolicy
{
    /**
     * GET /admin/logout
     */
    public function index(): bool
    {
        return true;
    }
}
