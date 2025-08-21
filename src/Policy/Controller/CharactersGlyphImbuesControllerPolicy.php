<?php
declare(strict_types=1);

namespace App\Policy\Controller;

use App\Model\Enum\Authorization;

class CharactersGlyphImbuesControllerPolicy extends ControllerPolicy
{
    /**
     * GET /characters/{plin}/{chin}/glyphimbues
     */
    public function charactersIndex(): bool
    {
        return $this->hasAuth(Authorization::ReadOnly, Authorization::Owner);
    }

    /**
     * PUT /characters/{plin}/{chin}/glyphimbues
     */
    public function charactersAdd(): bool
    {
        return $this->hasAuth(Authorization::Referee);
    }

    /**
     * GET /characters/{plin}/{chin}/glyphimbues/{id}
     */
    public function charactersView(): bool
    {
        return $this->charactersIndex();
    }

    /**
     * PUT /characters/{plin}/{chin}/glyphimbues/{id}
     */
    public function charactersEdit(): bool
    {
        return $this->charactersAdd();
    }

    /**
     * DELETE /characters/{plin}/{chin}/glyphimbues/{id}
     */
    public function charactersDelete(): bool
    {
        return $this->charactersAdd();
    }

    /**
     * GET /characters/{plin}/{chin}/glyphimbues/{id}/print
     */
    public function charactersPdf(): bool
    {
        return $this->charactersView();
    }

    /**
     * POST /characters/{plin}/{chin}/glyphimbues/{id}/print
     */
    public function charactersQueue(): bool
    {
        return $this->hasAuth(Authorization::Referee);
    }
}
