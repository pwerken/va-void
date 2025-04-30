<?php
declare(strict_types=1);

namespace App\Policy\Controller;

class CharactersPowersControllerPolicy extends ControllerPolicy
{
    // GET /characters/:plin/:chin/powers

    public function charactersIndex(): bool
    {
        return $this->hasAuth('player');
    }

    // PUT /characters/:plin/:chin/powers

    public function charactersAdd(): bool
    {
        return $this->hasAuth('referee');
    }

    // GET /characters/:plin/:chin/powers/:poin

    public function charactersView(): bool
    {
        return $this->charactersIndex();
    }

    // PUT /characters/:plin/:chin/powers/:poin

    public function charactersEdit(): bool
    {
        return $this->charactersAdd();
    }

    // DELETE /characters/:plin/:chin/powers/:poin

    public function charactersDelete(): bool
    {
        return $this->charactersAdd();
    }

    // POST /characters/:plin/:chin/powers/:poin/print

    public function charactersQueue(): bool
    {
        return $this->hasAuth('referee');
    }

    // GET /powers/:poin/characters
    public function powersIndex(): bool
    {
        return $this->hasAuth('read-only');
    }
}
