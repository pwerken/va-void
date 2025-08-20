<?php
declare(strict_types=1);

namespace App\Policy\Controller;

use App\Model\Enum\Authorization;

class TeachingsControllerPolicy extends ControllerPolicy
{
    /**
     * GET /characters/{plin}/{chin}/students
     */
    public function charactersIndex(): bool
    {
        return $this->hasAuth(Authorization::ReadOnly, Authorization::Owner);
    }

    /**
     * PUT /characters/{plin}/{chin}/teacher
     */
    public function charactersAdd(): bool
    {
        return $this->hasAuth(Authorization::Referee);
    }

    /**
     * GET /characters/{plin}/{chin}/teacher
     */
    public function charactersView(): bool
    {
        return $this->charactersIndex();
    }

    /**
     * PUT ??
     */
    public function charactersEdit(): bool
    {
        return false;
    }

    /**
     * DELETE /characters/{plin}/{chin}/teacher
     */
    public function charactersDelete(): bool
    {
        return $this->charactersAdd();
    }

    /**
     * POST /characters/{plin}/{chin}/teacher/print
     */
    public function charactersPdf(): bool
    {
        return $this->charactersView();
    }

    /**
     * POST /characters/{plin}/{chin}/teacher/print
     */
    public function charactersQueue(): bool
    {
        return $this->hasAuth(Authorization::Referee);
    }
}
