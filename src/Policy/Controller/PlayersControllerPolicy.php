<?php
declare(strict_types=1);

namespace App\Policy\Controller;

class PlayersControllerPolicy
    extends AppControllerPolicy
{
    public function add(): bool
    {
        return $this->hasAuth('infobalie');
    }

    public function delete(): bool
    {
        return $this->hasAuth('super');
    }

    public function edit(): bool
    {
        return $this->hasAuth('player');
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
