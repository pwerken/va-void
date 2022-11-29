<?php
declare(strict_types=1);

namespace App\Policy\Controller;

class LammiesControllerPolicy
    extends AppControllerPolicy
{
    public function add(): bool
    {
        return $this->hasAuth('super');
    }

    public function delete(): bool
    {
        return $this->hasAuth('super');
    }

    public function edit(): bool
    {
        return $this->add();
    }

    public function index(): bool
    {
        return $this->hasAuth('read-only');
    }

    public function view(): bool
    {
        $this->index();
    }

    public function queue(): bool
    {
        return $this->hasAuth('referee');
    }

    public function printed(): bool
    {
        return $this->hasAuth('infobalie');
    }

    public function pdfSingle(): bool
    {
        return $this->printed();
    }

    public function pdfDouble(): bool
    {
        return $this->printed();
    }
}
