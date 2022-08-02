<?php
declare(strict_types=1);

namespace App\Policy\Controller;

class PlayersControllerPolicy
    extends AppControllerPolicy
{
    public function add()
    {
        return $this->hasAuth('infobalie');
    }

    public function delete()
    {
        return $this->hasAuth('super');
    }

    public function edit()
    {
        return $this->hasAuth('player');
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
