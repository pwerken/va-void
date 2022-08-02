<?php
declare(strict_types=1);

namespace App\Policy\Controller;

class BelievesControllerPolicy
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
        return $this->hasAuth('infobalie');
    }

    public function index()
    {
        return $this->hasAuth('player');
    }

    public function view()
    {
        return $this->index();
    }
}
