<?php
declare(strict_types=1);

namespace App\Policy\Controller\Admin;

use App\Model\Enum\Authorization;
use App\Policy\Controller\ControllerPolicy;

class BackupsControllerPolicy extends ControllerPolicy
{
    /**
     * GET /admin/backups
     */
    public function index(): bool
    {
        return $this->hasAuth(Authorization::Infobalie);
    }
}
