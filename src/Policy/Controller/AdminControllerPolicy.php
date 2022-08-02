<?php
declare(strict_types=1);

namespace App\Policy\Controller;

class AdminControllerPolicy
    extends AppControllerPolicy
{
    public function index()
    {
        return true;
    }

    public function logout()
    {
        return true;
    }

    public function authentication()
    {
        return $this->hasAuth('player');
    }

    public function authorization()
    {
        return $this->hasAuth('read-only');
    }

    public function checks()
    {
        return true;
    }

    public function routes()
    {
        return true;
    }

    public function backups()
    {
        return $this->hasAuth('super');
    }

    public function migrations()
    {
        return $this->hasAuth('super');
    }

    public function history()
    {
        return $this->hasAuth('read-only');
    }

    public function printing()
    {
        return $this->hasAuth('read-only');
    }

    public function skills()
    {
        return $this->hasAuth('read-only');
    }

    public function valea_paid()
    {
        return $this->hasAuth('read-only');
    }

    public function valea_void()
    {
        return $this->hasAuth('infobalie');
    }
}
