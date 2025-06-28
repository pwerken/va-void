<?php
declare(strict_types=1);

namespace App\Policy\Controller\Admin;

use App\Policy\Controller\ControllerPolicy;

class PrintingControllerPolicy extends ControllerPolicy
{
    public function index(): bool
    {
        return $this->hasAuth('read-only');
    }

    public function double(): bool
    {
        return $this->hasAuth('infobalie');
    }

    public function single(): bool
    {
        return $this->double();
    }
}
