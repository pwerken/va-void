<?php
declare(strict_types=1);

namespace App\Policy\Controller;

class CharactersConditionsControllerPolicy
    extends AppControllerPolicy
{
    public function charactersAdd(): bool
    {
        return $this->hasAuth('referee');
    }

    public function charactersDelete(): bool
    {
        return $this->charactersAdd();
    }

    public function charactersEdit(): bool
    {
        return $this->charactersAdd();
    }

    public function charactersIndex(): bool
    {
        return $this->hasAuth('player');
    }

    public function charactersView(): bool
    {
        return $this->charactersIndex();
    }

    public function conditionsIndex(): bool
    {
        return $this->hasAuth('read-only');
    }

    public function charactersQueue(): bool
    {
        return $this->hasAuth('referee');
    }
}
