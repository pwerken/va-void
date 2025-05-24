<?php
declare(strict_types=1);

namespace App\Policy\Controller;

class PlayersSocialsControllerPolicy extends ControllerPolicy
{
    /**
     * GET /players/{plin}/socials
     */
    public function playersIndex(): bool
    {
        return $this->hasAuth('player');
    }

    /**
     * PUT /players/{plin}/socials
     */
    public function playersAdd(): bool
    {
        return false;
    }

    /**
     * GET /players/{plin}/socials/{id}
     */
    public function playersView(): bool
    {
        return $this->hasAuth('player');
    }

    /**
     * PUT /players/{plin}/socials/{id}
     */
    public function playersEdit(): bool
    {
        return false;
    }

    /**
     * DELETE /players/{plin}/socials/{id}
     */
    public function playersDelete(): bool
    {
        return $this->hasAuth('player');
    }
}
