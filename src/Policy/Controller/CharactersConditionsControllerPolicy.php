<?php
declare(strict_types=1);

namespace App\Policy\Controller;

class CharactersConditionsControllerPolicy
    extends AppControllerPolicy
{
    // GET /characters/:plin/:chin/conditions
    public function charactersIndex(): bool
    {
        return $this->hasAuth('player');
    }

    // PUT /characters/:plin/:chin/conditions
    public function charactersAdd(): bool
    {
        return $this->hasAuth('referee');
    }

    // GET /characters/:plin/:chin/conditions/:coin
    public function charactersView(): bool
    {
        return $this->charactersIndex();
    }

    // PUT /characters/:plin/:chin/conditions/:coin
    public function charactersEdit(): bool
    {
        return $this->charactersAdd();
    }

    // DELETE /characters/:plin/:chin/conditions/:coin
    public function charactersDelete(): bool
    {
        return $this->charactersAdd();
    }

    // POST /characters/:plin/:chin/conditions/:coin/print
    public function charactersQueue(): bool
    {
        return $this->hasAuth('referee');
    }

    // GET /condition/:coin/characters
    public function conditionsIndex(): bool
    {
        return $this->hasAuth('read-only');
    }
}
