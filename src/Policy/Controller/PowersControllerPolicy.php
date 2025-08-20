<?php
declare(strict_types=1);

namespace App\Policy\Controller;

use App\Model\Enum\Authorization;

class PowersControllerPolicy extends ControllerPolicy
{
    /**
     * GET /powers
     */
    public function index(): bool
    {
        return $this->hasAuth(Authorization::Player);
    }

    /**
     * PUT /powers
     */
    public function add(): bool
    {
        return $this->hasAuth(Authorization::Referee);
    }

    /**
     * GET /powers/{poin}
     */
    public function view(): bool
    {
        return $this->index();
    }

    /**
     * PUT /powers/{poin}
     */
    public function edit(): bool
    {
        return $this->add();
    }

    /**
     * DELETE /powers/{poin}
     */
    public function delete(): bool
    {
        return $this->hasAuth(Authorization::Super);
    }

    /**
     * GET /powers/{poin}/print
     */
    public function pdf(): bool
    {
        return $this->hasAuth(Authorization::ReadOnly);
    }

    /**
     * POST /powers/{poin}/print
     */
    public function queue(): bool
    {
        return $this->hasAuth(Authorization::Referee);
    }
}
