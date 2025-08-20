<?php
declare(strict_types=1);

namespace App\Policy\Controller;

use App\Model\Enum\Authorization;

class CharactersSkillsControllerPolicy extends ControllerPolicy
{
    /**
     * GET /characters/{plin}/{chin}/skills
     */
    public function charactersIndex(): bool
    {
        return $this->hasAuth(Authorization::ReadOnly, Authorization::Owner);
    }

    /**
     * PUT /characters/{plin}/{chin}/skills
     */
    public function charactersAdd(): bool
    {
        return $this->hasAuth(Authorization::Referee);
    }

    /**
     * GET /characters/{plin}/{chin}/skills/{id}
     */
    public function charactersView(): bool
    {
        return $this->charactersIndex();
    }

    /**
     * PUT /characters/{plin}/{chin}/skills/{id}
     */
    public function charactersEdit(): bool
    {
        return $this->charactersAdd();
    }

    /**
     * DELETE /characters/{plin}/{chin}/skills/{id}
     */
    public function charactersDelete(): bool
    {
        return $this->charactersAdd();
    }

    /**
     * GET /skills/{id}/characters
     */
    public function skillsIndex(): bool
    {
        return $this->hasAuth(Authorization::ReadOnly);
    }
}
