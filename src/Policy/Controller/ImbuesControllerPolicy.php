<?php
declare(strict_types=1);

namespace App\Policy\Controller;

use App\Model\Enum\Authorization;

class ImbuesControllerPolicy extends ControllerPolicy
{
    /**
     * GET /imbues
     */
    public function index(): bool
    {
        return $this->hasAuth(Authorization::Player);
    }

    /**
     * PUT /imbues
     */
    public function add(): bool
    {
        return $this->hasAuth(Authorization::Referee);
    }

    /**
     * GET /imbues/{id}
     */
    public function view(): bool
    {
        return $this->index();
    }

    /**
     * PUT /imbues/{id}
     */
    public function edit(): bool
    {
        return $this->add();
    }

    /**
     * DELETE /imbues/{id}
     */
    public function delete(): bool
    {
        return $this->hasAuth(Authorization::Super);
    }
}
