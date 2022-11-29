<?php
declare(strict_types=1);

namespace App\Policy\Controller;

class ItemsControllerPolicy
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

    public function charactersIndex(): bool
    {
        return $this->hasAuth('player');
    }

    public function queue(): bool
    {
        return $this->add();
    }
}
