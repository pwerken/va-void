<?php
declare(strict_types=1);

namespace App\Policy\Controller;

class CharactersControllerPolicy
    extends AppControllerPolicy
{
    public function add(): bool
    {
        return $this->hasAuth('referee');
    }

    public function delete(): bool
    {
        return $this->hasAuth('super');
    }

    public function edit(): bool
    {
        return $this->add();
    }

    public function index(): bool
    {
        return $this->hasAuth('player');
    }

    public function view(): bool
    {
        return $this->index();
    }

    public function queue(): bool
    {
        return $this->add();
    }

    public function believesIndex(): bool
    {
        return $this->hasAuth('read-only');
    }

    public function factionsIndex(): bool
    {
        return $this->hasAuth('read-only');
    }

    public function groupsIndex(): bool
    {
        return $this->hasAuth('read-only');
    }

    public function playersIndex(): bool
    {
        return $this->hasAuth('player');
    }

    public function worldsIndex(): bool
    {
        return $this->hasAuth('read-only');
    }
}
