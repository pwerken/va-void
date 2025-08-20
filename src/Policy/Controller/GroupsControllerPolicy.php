<?php
declare(strict_types=1);

namespace App\Policy\Controller;

use App\Model\Enum\Authorization;

class GroupsControllerPolicy extends ControllerPolicy
{
    /**
     * GET /groups
     */
    public function index(): bool
    {
        return $this->hasAuth(Authorization::Player);
    }
}
