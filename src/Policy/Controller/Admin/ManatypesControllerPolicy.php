<?php
declare(strict_types=1);

namespace App\Policy\Controller\Admin;

use App\Model\Enum\Authorization;
use App\Policy\Controller\ControllerPolicy;

class ManatypesControllerPolicy extends ControllerPolicy
{
    /**
     * GET /admin/manatypes
     */
    public function index(): bool
    {
        return $this->hasAuth(Authorization::ReadOnly);
    }

    /**
     * GET /admin/manatypes/add
     * POST /admin/manatypes/add
     */
    public function add(): bool
    {
        return $this->hasAuth(Authorization::Super);
    }

    /**
     * GET /admin/manatypes/edit/{id}
     * POST /admin/manatypes/edit/{id}
     */
    public function edit(): bool
    {
        return $this->add();
    }

    /**
     * POST /admin/manatypes/delete/{id}
     */
    public function delete(): bool
    {
        return $this->add();
    }
}
