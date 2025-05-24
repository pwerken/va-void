<?php
declare(strict_types=1);

namespace App\Policy\Controller;

class PlayersControllerPolicy extends ControllerPolicy
{
    /**
     * GET /players
     */
    public function index(): bool
    {
        return $this->hasAuth('player');
    }

    /**
     * PUT /players
     */
    public function add(): bool
    {
        return $this->hasAuth('infobalie');
    }

    /**
     * GET /players/{plin}
     */
    public function view(): bool
    {
        return $this->index();
    }

    /**
     * PUT /players/{plin}
     */
    public function edit(): bool
    {
        return $this->hasAuth('player');
    }

    /**
     * DELETE /players/{plin}
     */
    public function delete(): bool
    {
        return $this->add();
    }
}
