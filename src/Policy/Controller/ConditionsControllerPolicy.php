<?php
declare(strict_types=1);

namespace App\Policy\Controller;

use App\Model\Enum\Authorization;

class ConditionsControllerPolicy extends ControllerPolicy
{
    /**
     * GET /conditions
     */
    public function index(): bool
    {
        return $this->hasAuth(Authorization::Player);
    }

    /**
     * PUT /conditions
     */
    public function add(): bool
    {
        return $this->hasAuth(Authorization::Referee);
    }

    /**
     * GET /conditions/{coin}
     */
    public function view(): bool
    {
        return $this->index();
    }

    /**
     * PUT /conditions/{coin}
     */
    public function edit(): bool
    {
        return $this->add();
    }

    /**
     * DELETE /conditions/{coin}
     */
    public function delete(): bool
    {
        return $this->hasAuth(Authorization::Super);
    }

    /**
     * GET /conditions/{coin}/print
     */
    public function pdf(): bool
    {
        return $this->hasAuth(Authorization::ReadOnly);
    }

    /**
     * POST /conditions/{coin}/print
     */
    public function queue(): bool
    {
        return $this->hasAuth(Authorization::Referee);
    }
}
