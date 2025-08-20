<?php
declare(strict_types=1);

namespace App\Policy\Controller;

use App\Model\Enum\Authorization;

class PlayersControllerPolicy extends ControllerPolicy
{
    /**
     * GET /players
     */
    public function index(): bool
    {
        return $this->hasAuth(Authorization::Player);
    }

    /**
     * PUT /players
     */
    public function add(): bool
    {
        return $this->hasAuth(Authorization::Infobalie);
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
        return $this->hasAuth(Authorization::Player);
    }

    /**
     * DELETE /players/{plin}
     */
    public function delete(): bool
    {
        return $this->add();
    }
}
