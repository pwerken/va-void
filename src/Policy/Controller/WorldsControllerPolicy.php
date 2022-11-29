<?php
declare(strict_types=1);

namespace App\Policy\Controller;

class WorldsControllerPolicy
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
        return $this->hasAuth('infobalie');
    }

    public function index(): bool
    {
        return $this->hasAuth('player');
    }

    public function view(): bool
    {
        return $this->index();
    }
}
