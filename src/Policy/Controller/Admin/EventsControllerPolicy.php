<?php
declare(strict_types=1);

namespace App\Policy\Controller\Admin;

use App\Model\Enum\Authorization;
use App\Policy\Controller\ControllerPolicy;

class EventsControllerPolicy extends ControllerPolicy
{
    /**
     * GET /admin/events
     */
    public function index(): bool
    {
        return $this->hasAuth(Authorization::ReadOnly);
    }

    /**
     * GET /admin/events/add
     * POST /admin/events/add
     */
    public function add(): bool
    {
        return $this->hasAuth(Authorization::Super);
    }

    /**
     * GET /admin/events/edit/{id}
     * POST /admin/events/edit/{id}
     */
    public function edit(): bool
    {
        return $this->add();
    }

    /**
     * POST /admin/events/delete/{id}
     */
    public function delete(): bool
    {
        return $this->add();
    }
}
