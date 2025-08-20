<?php
declare(strict_types=1);

namespace App\Policy\Controller;

use App\Model\Enum\Authorization;

class CharactersConditionsControllerPolicy extends ControllerPolicy
{
    /**
     * GET /characters/{plin}/{chin}/conditions
     */
    public function charactersIndex(): bool
    {
        return $this->hasAuth(Authorization::ReadOnly, Authorization::Owner);
    }

    /**
     * PUT /characters/{plin}/{chin}/conditions
     */
    public function charactersAdd(): bool
    {
        return $this->hasAuth(Authorization::Referee);
    }

    /**
     * GET /characters/{plin}/{chin}/conditions/{coin}
     */
    public function charactersView(): bool
    {
        return $this->charactersIndex();
    }

    /**
     * PUT /characters/{plin}/{chin}/conditions/{coin}
     */
    public function charactersEdit(): bool
    {
        return $this->charactersAdd();
    }

    /**
     * DELETE /characters/{plin}/{chin}/conditions/{coin}
     */
    public function charactersDelete(): bool
    {
        return $this->charactersAdd();
    }

    /**
     * GET /characters/{plin}/{chin}/conditions/{coin}/print
     */
    public function charactersPdf(): bool
    {
        return $this->charactersView();
    }

    /**
     * POST /characters/{plin}/{chin}/conditions/{coin}/print
     */
    public function charactersQueue(): bool
    {
        return $this->hasAuth(Authorization::Referee);
    }

    /**
     * GET /condition/{coin}/characters
     */
    public function conditionsIndex(): bool
    {
        return $this->hasAuth(Authorization::ReadOnly);
    }
}
