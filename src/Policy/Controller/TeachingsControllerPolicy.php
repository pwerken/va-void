<?php
declare(strict_types=1);

namespace App\Policy\Controller;

class TeachingsControllerPolicy
    extends AppControllerPolicy
{
    public function charactersAdd()
    {
        return $this->hasAuth('infobalie');
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

    public function charactersQueue()
    {
        return $this->hasAuth('referee');
    }
}
