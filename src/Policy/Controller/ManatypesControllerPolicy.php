<?php
declare(strict_types=1);

namespace App\Policy\Controller;

use App\Model\Enum\Authorization;

class ManatypesControllerPolicy extends ControllerPolicy
{
    /**
     * GET /manatypes
     */
    public function index(): bool
    {
        return $this->hasAuth(Authorization::Player);
    }

    /**
     * PUT /manatypes
     */
    public function add(): bool
    {
        return $this->hasAuth(Authorization::Super);
    }

    /**
     * GET /manatypes/{id}
     */
    public function view(): bool
    {
        return $this->index();
    }

    /**
     * PUT /manatypes/{id}
     */
    public function edit(): bool
    {
        return $this->add();
    }

    /**
     * DELETE /manatypes/{id}
     */
    public function delete(): bool
    {
        return $this->add();
    }
}
