<?php
declare(strict_types=1);

namespace App\Policy\Controller\Admin;

use App\Model\Enum\Authorization;
use App\Policy\Controller\ControllerPolicy;

class SkillsControllerPolicy extends ControllerPolicy
{
    /**
     * GET /admin/skills
     */
    public function index(): bool
    {
        return $this->hasAuth(Authorization::ReadOnly);
    }

    /**
     * GET /admin/skills/add
     * POST /admin/skills/add
     */
    public function add(): bool
    {
        return $this->hasAuth(Authorization::Super);
    }

    /**
     * GET /admin/skills/edit/{id}
     * POST /admin/skills/edit/{id}
     */
    public function edit(): bool
    {
        return $this->add();
    }

    /**
     * POST /admin/skills/delete/{id}
     */
    public function delete(): bool
    {
        return $this->add();
    }
}
