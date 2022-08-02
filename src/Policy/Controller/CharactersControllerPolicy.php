<?php
declare(strict_types=1);

namespace App\Policy\Controller;

class CharactersControllerPolicy
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

    public function believesIndex()
    {
        return $this->hasAuth('read-only');
    }

    public function factionsIndex()
    {
        return $this->hasAuth('read-only');
    }

    public function groupsIndex()
    {
        return $this->hasAuth('read-only');
    }

    public function playersIndex()
    {
        return $this->hasAuth('player');
    }

    public function worldsIndex()
    {
        return $this->hasAuth('read-only');
    }
}
