<?php
declare(strict_types=1);

namespace App\Policy\Controller;

class CharactersSkillsControllerPolicy
    extends AppControllerPolicy
{
    // GET /characters/:plin/:chin/skills
    public function charactersIndex(): bool
    {
        return $this->hasAuth('player');
    }

    // PUT /characters/:plin/:chin/skills
    public function charactersAdd(): bool
    {
        return $this->hasAuth('referee');
    }

    // GET /characters/:plin/:chin/skills/:id
    public function charactersView(): bool
    {
        return $this->charactersIndex();
    }

    // PUT /characters/:plin/:chin/skills/:id
    public function charactersEdit(): bool
    {
        return false;
    }

    // DELETE /characters/:plin/:chin/skills/:id
    public function charactersDelete(): bool
    {
        return $this->charactersAdd();
    }

    // GET /skills/:id/characters
    public function skillsIndex(): bool
    {
        return $this->hasAuth('read-only');
    }
}
