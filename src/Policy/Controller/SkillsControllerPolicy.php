<?php
declare(strict_types=1);

namespace App\Policy\Controller;

use App\Model\Enum\Authorization;

class SkillsControllerPolicy extends ControllerPolicy
{
    /**
     * GET /skills
     */
    public function index(): bool
    {
        return $this->hasAuth(Authorization::Player);
    }

    /**
     * PUT /skills
     */
    public function add(): bool
    {
        return $this->hasAuth(Authorization::Super);
    }

    /**
     * GET /skills/{id}
     */
    public function view(): bool
    {
        return $this->index();
    }

    /**
     * PUT /skills/{id}
     */
    public function edit(): bool
    {
        return $this->add();
    }

    /**
     * DELETE /skills/{id}
     */
    public function delete(): bool
    {
        return $this->add();
    }
}
