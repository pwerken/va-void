<?php
declare(strict_types=1);

namespace App\Policy\Controller;

class CharactersSpellsControllerPolicy
    extends AppControllerPolicy
{
    public function charactersAdd()
    {
        return $this->hasAuth('referee');
    }

    public function charactersDelete()
    {
        return $this->charactersAdd();
    }

    public function charactersEdit()
    {
        return $this->charactersAdd();
    }

    public function charactersIndex()
    {
        return $this->hasAuth('player');
    }

    public function charactersView()
    {
        return $this->charactersIndex();
    }

    public function spellsIndex()
    {
        return $this->hasAuth('read-only');
    }
}
