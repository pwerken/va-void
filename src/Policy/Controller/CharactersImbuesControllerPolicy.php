<?php
declare(strict_types=1);

namespace App\Policy\Controller;

use App\Model\Enum\Authorization;

class CharactersImbuesControllerPolicy extends ControllerPolicy
{
    /**
     * GET /imbues/{id}/characters
     */
    public function imbuesIndex(): bool
    {
        return $this->hasAuth(Authorization::ReadOnly);
    }
}
