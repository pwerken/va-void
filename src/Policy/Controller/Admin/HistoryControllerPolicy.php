<?php
declare(strict_types=1);

namespace App\Policy\Controller\Admin;

use App\Policy\Controller\ControllerPolicy;

class HistoryControllerPolicy extends ControllerPolicy
{
    /**
     * GET /admin/history
     */
    public function index(): bool
    {
        return $this->hasAuth('read-only');
    }

    /**
     * GET /admin/history/player/$plin
     */
    public function player(): bool
    {
        return $this->index();
    }

    /**
     * GET /admin/history/character/$plin/$chin
     */
    public function character(): bool
    {
        return $this->index();
    }

    /**
     * GET /admin/history/item/$itin
     */
    public function item(): bool
    {
        return $this->index();
    }

    /**
     * GET /admin/history/condition/$coin
     */
    public function condition(): bool
    {
        return $this->index();
    }

    /**
     * GET /admin/history/power/$poin
     */
    public function power(): bool
    {
        return $this->index();
    }
}
