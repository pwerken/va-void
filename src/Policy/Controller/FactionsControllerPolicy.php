<?php
declare(strict_types=1);

namespace App\Policy\Controller;

class FactionsControllerPolicy extends ControllerPolicy
{
    /**
     * GET /factions
     */
    public function index(): bool
    {
        return $this->hasAuth('player');
    }

    /**
     * PUT /factions
     */
    public function add(): bool
    {
        return false; //$this->hasAuth('super');
    }

    /**
     * GET /factions/{id}
     */
    public function view(): bool
    {
        return $this->index();
    }

    /**
     * PUT /factions/{id}
     */
    public function edit(): bool
    {
        return $this->add();
    }

    /**
     * DELETE /factions/{id}
     */
    public function delete(): bool
    {
        return $this->add();
    }
}
