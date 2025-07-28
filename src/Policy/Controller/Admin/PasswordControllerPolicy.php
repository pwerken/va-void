<?php
declare(strict_types=1);

namespace App\Policy\Controller\Admin;

use App\Policy\Controller\ControllerPolicy;

class PasswordControllerPolicy extends ControllerPolicy
{
    /**
     * GET /admin/password
     */
    public function index(): bool
    {
        return $this->hasAuth('player');
    }

    /**
     * POST /admin/password/edit
     */
    public function edit(): bool
    {
        return $this->index();
    }
}
