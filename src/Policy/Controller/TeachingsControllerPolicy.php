<?php
declare(strict_types=1);

namespace App\Policy\Controller;

class TeachingsControllerPolicy
    extends AppControllerPolicy
{
    // GET /characters/:plin/:chin/students
    public function charactersIndex(): bool
    {
        return $this->hasAuth('player');
    }

    // PUT ??
    public function charactersAdd(): bool
    {
        return $this->hasAuth('infobalie');
    }

    // GET /characters/:plin/:chin/teacher
    public function charactersView(): bool
    {
        return $this->charactersIndex();
    }

    // PUT /characters/:plin/:chin/teacher
    public function charactersEdit(): bool
    {
        return $this->charactersAdd();
    }

    // DELETE /characters/:plin/:chin/teacher
    public function charactersDelete(): bool
    {
        return $this->charactersAdd();
    }

    // POST /characters/:plin/:chin/teacher/print
    public function charactersQueue(): bool
    {
        return $this->hasAuth('referee');
    }
}
