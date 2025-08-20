<?php
declare(strict_types=1);

namespace App\Policy\Controller;

use App\Model\Enum\Authorization;

class LammiesControllerPolicy extends ControllerPolicy
{
    /**
     * GET /lammies
     */
    public function index(): bool
    {
        return $this->hasAuth(Authorization::ReadOnly);
    }

    /**
     * PUT /lammies
     */
    public function add(): bool
    {
        return $this->hasAuth(Authorization::Super);
    }

    /**
     * GET /lammies/{id}
     */
    public function view(): bool
    {
        return $this->index();
    }

    /**
     * PUT /lammies/{id}
     */
    public function edit(): bool
    {
        return $this->add();
    }

    /**
     * DELETE /lammies/{id}
     */
    public function delete(): bool
    {
        return $this->add();
    }

    /**
     * GET /lammies/queue
     */
    public function queue(): bool
    {
        return $this->hasAuth(Authorization::Infobalie);
    }

    /**
     * POST /lammies/printed
     */
    public function printed(): bool
    {
        return $this->queue();
    }

    /**
     * POST /lammies/single
     */
    public function pdfSingle(): bool
    {
        return $this->queue();
    }

    /**
     * POST /lammies/double
     */
    public function pdfDouble(): bool
    {
        return $this->queue();
    }
}
