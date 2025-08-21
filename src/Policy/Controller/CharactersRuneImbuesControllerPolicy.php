<?php
declare(strict_types=1);

namespace App\Policy\Controller;

use App\Model\Enum\Authorization;

class CharactersRuneImbuesControllerPolicy extends ControllerPolicy
{
    /**
     * GET /characters/{plin}/{chin}/runeimbues
     */
    public function charactersIndex(): bool
    {
        return $this->hasAuth(Authorization::ReadOnly, Authorization::Owner);
    }

    /**
     * PUT /characters/{plin}/{chin}/runeimbues
     */
    public function charactersAdd(): bool
    {
        return $this->hasAuth(Authorization::Referee);
    }

    /**
     * GET /characters/{plin}/{chin}/runeimbues/{id}
     */
    public function charactersView(): bool
    {
        return $this->charactersIndex();
    }

    /**
     * PUT /characters/{plin}/{chin}/runeimbues/{id}
     */
    public function charactersEdit(): bool
    {
        return $this->charactersAdd();
    }

    /**
     * DELETE /characters/{plin}/{chin}/runeimbues/{id}
     */
    public function charactersDelete(): bool
    {
        return $this->charactersAdd();
    }

    /**
     * GET /characters/{plin}/{chin}/runeimbues/{id}/print
     */
    public function charactersPdf(): bool
    {
        return $this->charactersView();
    }

    /**
     * POST /characters/{plin}/{chin}/runeimbues/{id}/print
     */
    public function charactersQueue(): bool
    {
        return $this->hasAuth(Authorization::Referee);
    }
}
