<?php
declare(strict_types=1);

namespace App\Policy\Controller;

class LammiesControllerPolicy
    extends AppControllerPolicy
{
    public function add()
    {
        return $this->hasAuth('super');
    }

    public function delete()
    {
        return $this->hasAuth('super');
    }

    public function edit()
    {
        return $this->add();
    }

    public function index()
    {
        return $this->hasAuth('read-only');
    }

    public function view()
    {
        $this->index();
    }

    public function queue()
    {
        return $this->hasAuth('referee');
    }

    public function printed()
    {
        return $this->hasAuth('infobalie');
    }

    public function pdfSingle()
    {
        return $this->printed();
    }

    public function pdfDouble()
    {
        return $this->printed();
    }
}
