<?php
declare(strict_types=1);

namespace App\Policy\Controller;

use App\Model\Enum\Authorization;

class ItemsControllerPolicy extends ControllerPolicy
{
    /**
     * GET /items
     */
    public function index(): bool
    {
        return $this->hasAuth(Authorization::Player);
    }

    /**
     * PUT /items
     */
    public function add(): bool
    {
        return $this->hasAuth(Authorization::Referee);
    }

    /**
     * GET /items/{itin}
     */
    public function view(): bool
    {
        return $this->index();
    }

    /**
     * PUT /items/{itin}
     */
    public function edit(): bool
    {
        return $this->add();
    }

    /**
     * DELETE /items/{itin}
     */
    public function delete(): bool
    {
        return $this->hasAuth(Authorization::Super);
    }

    /**
     * GET /items/{itin}/print
     */
    public function pdf(): bool
    {
        return false;
    }

    /**
     * POST /items/{itin}/print
     */
    public function queue(): bool
    {
        return $this->hasAuth(Authorization::Referee);
    }

    /**
     * GET /character/{plin}/{chin}/items
     */
    public function charactersIndex(): bool
    {
        return $this->hasAuth(Authorization::ReadOnly, Authorization::Owner);
    }
}
