<?php
declare(strict_types=1);

namespace App\Policy\Controller;

class CharactersSpellsControllerPolicy
    extends AppControllerPolicy
{
    // GET /characters/:plin/:chin/spells
    public function charactersIndex(): bool
    {
        return $this->hasAuth('player');
    }

    // PUT /characters/:plin/:chin/spells
    public function charactersAdd(): bool
    {
        return $this->hasAuth('referee');
    }

    // GET /characters/:plin/:chin/spells/:id
    public function charactersView(): bool
    {
        return $this->charactersIndex();
    }

    // PUT /characters/:plin/:chin/spells/:id
    public function charactersEdit(): bool
    {
        return $this->charactersAdd();
    }

    // DELETE /characters/:plin/:chin/spells/:id
    public function charactersDelete(): bool
    {
        return $this->charactersAdd();
    }

    // GET /spells/:id/characters
    public function spellsIndex(): bool
    {
        return $this->hasAuth('read-only');
    }
}
