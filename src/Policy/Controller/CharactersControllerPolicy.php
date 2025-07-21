<?php
declare(strict_types=1);

namespace App\Policy\Controller;

class CharactersControllerPolicy extends ControllerPolicy
{
    /**
     * GET /characters
     */
    public function index(): bool
    {
        return $this->hasAuth('player');
    }

    /**
     * PUT /players/{plin}/characters
     */
    public function add(): bool
    {
        return $this->hasAuth('referee');
    }

    /**
     * GET /characters/{plin}/{chin}
     */
    public function view(): bool
    {
        return $this->hasAuth(['user', 'read-only']);
    }

    /**
     * PUT /characters/{plin}/{chin}
     */
    public function edit(): bool
    {
        return $this->add();
    }

    /**
     * DELETE /characters/{plin}/{chin}
     */
    public function delete(): bool
    {
        return $this->hasAuth('super');
    }

    /**
     * GET /characters/{plin}/{chin}/print
     */
    public function pdf(): bool
    {
        return $this->view();
    }

    /**
     * POST /characters/{plin}/{chin}/print
     */
    public function queue(): bool
    {
        return $this->hasAuth('referee');
    }

    /**
     * GET /characters/{plin}
     */
    public function playersIndex(): bool
    {
        return $this->view();
    }

    /**
     * GET /factions/{id}/characters
     */
    public function factionsIndex(): bool
    {
        return $this->hasAuth('read-only');
    }
}
