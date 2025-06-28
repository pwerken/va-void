<?php
declare(strict_types=1);

namespace App\Policy\Controller\Admin;

use App\Policy\Controller\ControllerPolicy;

class HistoryControllerPolicy extends ControllerPolicy
{
    public function index(): bool
    {
        return $this->hasAuth('read-only');
    }

    public function player(): bool
    {
        return $this->index();
    }

    public function character(): bool
    {
        return $this->index();
    }

    public function item(): bool
    {
        return $this->index();
    }

    public function condition(): bool
    {
        return $this->index();
    }

    public function power(): bool
    {
        return $this->index();
    }
}
