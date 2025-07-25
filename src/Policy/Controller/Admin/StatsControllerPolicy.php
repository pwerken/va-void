<?php
declare(strict_types=1);

namespace App\Policy\Controller\Admin;

use App\Policy\Controller\ControllerPolicy;

class StatsControllerPolicy extends ControllerPolicy
{
    /**
     * GET /stats
     */
    public function index(): bool
    {
        return $this->hasAuth('read-only');
    }
}
