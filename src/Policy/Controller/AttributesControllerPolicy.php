<?php
declare(strict_types=1);

namespace App\Policy\Controller;

class AttributesControllerPolicy
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
        return $this->hasAuth('super');
    }

    public function index()
    {
        return $this->hasAuth('read-only');
    }

    public function view()
    {
        return $this->index();
    }
}
