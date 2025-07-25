<?php
declare(strict_types=1);

namespace App\Policy\Controller\Admin;

use App\Policy\Controller\ControllerPolicy;

class PrintingControllerPolicy extends ControllerPolicy
{
    /**
     * GET /printing
     * POST /printing
     */
    public function index(): bool
    {
        return $this->hasAuth('read-only');
    }

    /**
     * GET /printing/double
     */
    public function double(): bool
    {
        return $this->hasAuth('infobalie');
    }

    /**
     * GET /printing/single
     */
    public function single(): bool
    {
        return $this->double();
    }
}
