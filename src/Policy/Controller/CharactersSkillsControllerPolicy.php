<?php
declare(strict_types=1);

namespace App\Policy\Controller;

class CharactersSkillsControllerPolicy
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
        return false; #$this->charactersAdd();
    }

    public function charactersIndex()
    {
        return $this->hasAuth('player');
    }

    public function charactersView()
    {
        return $this->charactersIndex();
    }

    public function skillsIndex()
    {
        return $this->hasAuth('read-only');
    }
}
