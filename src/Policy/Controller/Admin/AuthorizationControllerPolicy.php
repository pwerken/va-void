<?php
declare(strict_types=1);

namespace App\Policy\Controller\Admin;

use App\Model\Enum\Authorization;
use App\Policy\Controller\ControllerPolicy;

class AuthorizationControllerPolicy extends ControllerPolicy
{
    /**
     * GET /admin/authorization
     */
    public function index(): bool
    {
        return $this->hasAuth(Authorization::ReadOnly);
    }

    /**
     * POST /admin/authorization/edit
     */
    public function edit(): bool
    {
        return $this->index();
    }
}
