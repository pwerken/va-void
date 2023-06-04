<?php
declare(strict_types=1);

namespace App\Policy\Controller;

class CharactersControllerPolicy
    extends AppControllerPolicy
{
    // GET /characters
    public function index(): bool
    {
        return $this->hasAuth('player');
    }

    // PUT /players/:plin/characters
    public function add(): bool
    {
        return $this->hasAuth('referee');
    }

    // GET /characters/:plin/:chin
    public function view(): bool
    {
        return $this->index();
    }

    // PUT /characters/:plin/:chin
    public function edit(): bool
    {
        return $this->add();
    }

    // DELETE /characters/:plin/:chin
    public function delete(): bool
    {
        return $this->hasAuth('super');
    }

    // POST /characters/:plin/:chin/print
    public function queue(): bool
    {
        return $this->hasAuth('referee');
    }

    // GET /characters/:plin
    public function playersIndex(): bool
    {
        return $this->hasAuth('player');
    }

    // GET /believes/:id/characters
    public function believesIndex(): bool
    {
        return $this->hasAuth('read-only');
    }

    // GET /factions/:id/characters
    public function factionsIndex(): bool
    {
        return $this->hasAuth('read-only');
    }

    // GET /groups/:id/characters
    public function groupsIndex(): bool
    {
        return $this->hasAuth('read-only');
    }

    // GET /worlds/:id/characters
    public function worldsIndex(): bool
    {
        return $this->hasAuth('read-only');
    }
}
