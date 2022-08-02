<?php
declare(strict_types=1);

namespace App\Policy\Controller;

class CharactersConditionsControllerPolicy
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

    public function conditionsIndex()
    {
        return $this->hasAuth('player');
    }

    public function charactersQueue()
    {
        return $this->hasAuth('referee');
    }
}
