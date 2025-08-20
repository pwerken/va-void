<?php
declare(strict_types=1);

namespace App\Policy\Controller;

use App\Model\Enum\Authorization;

class WorldsControllerPolicy extends ControllerPolicy
{
    /**
     * GET /worlds
     */
    public function index(): bool
    {
        return $this->hasAuth(Authorization::Player);
    }
}
