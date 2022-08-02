<?php
declare(strict_types=1);

namespace App\Policy\Controller;

class PowersControllerPolicy
    extends AppControllerPolicy
{
    public function add()
    {
        return $this->hasAuth('referee');
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
        return $this->hasAuth('player');
    }

    public function view()
    {
        return $this->index();
    }

    public function queue()
    {
        return $this->add();
    }
}
