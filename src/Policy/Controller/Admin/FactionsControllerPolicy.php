<?php
declare(strict_types=1);

namespace App\Policy\Controller\Admin;

use App\Model\Enum\Authorization;
use App\Policy\Controller\ControllerPolicy;

class FactionsControllerPolicy extends ControllerPolicy
{
    /**
     * GET /admin/factions
     */
    public function index(): bool
    {
        return $this->hasAuth(Authorization::ReadOnly);
    }

    /**
     * GET /admin/factions/add
     * POST /admin/factions/add
     */
    public function add(): bool
    {
        return $this->hasAuth(Authorization::Super);
    }

    /**
     * GET /admin/factions/edit/{id}
     * POST /admin/factions/edit/{id}
     */
    public function edit(): bool
    {
        return $this->add();
    }

    /**
     * POST /admin/factions/delete/{id}
     */
    public function delete(): bool
    {
        return $this->add();
    }
}
