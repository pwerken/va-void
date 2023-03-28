<?php
declare(strict_types=1);

namespace App\Policy\Controller;

class AdminControllerPolicy
    extends AppControllerPolicy
{
    public function index(): bool
    {
        return true;
    }

    public function logout(): bool
    {
        return true;
    }

    public function authentication(): bool
    {
        return $this->hasAuth('player');
    }

    public function authorization(): bool
    {
        return $this->hasAuth('read-only');
    }

    public function checks(): bool
    {
        return true;
    }

    public function routes(): bool
    {
        return true;
    }

    public function backups(): bool
    {
        return $this->hasAuth('super');
    }

    public function migrations(): bool
    {
        return $this->hasAuth('super');
    }

    public function history(): bool
    {
        return $this->hasAuth('read-only');
    }

    public function printing(): bool
    {
        return $this->hasAuth('read-only');
    }

    public function skills(): bool
    {
        return $this->hasAuth('read-only');
    }

    public function valea_paid(): bool
    {
        return $this->hasAuth('read-only');
    }

    public function valea_void(): bool
    {
        return $this->hasAuth('infobalie');
    }
}