<?php
declare(strict_types=1);

namespace App\Policy\Controller;

class EventsControllerPolicy extends ControllerPolicy
{
    /**
     * GET /events
     */
    public function index(): bool
    {
        return $this->hasAuth('player');
    }

    /**
     * PUT /events
     */
    public function add(): bool
    {
        return $this->hasAuth('super');
    }

    /**
     * GET /events/{id}
     */
    public function view(): bool
    {
        return $this->index();
    }

    /**
     * PUT /events/{id}
     */
    public function edit(): bool
    {
        return $this->add();
    }

    /**
     * DELETE /events/{id}
     */
    public function delete(): bool
    {
        return $this->add();
    }
}
