<?php
declare(strict_types=1);

namespace App\Policy\Controller\Admin;

use App\Model\Enum\Authorization;
use App\Policy\Controller\ControllerPolicy;

class SkillsControllerPolicy extends ControllerPolicy
{
    /**
     * GET /skills
     */
    public function index(): bool
    {
        return $this->hasAuth(Authorization::ReadOnly);
    }
}
