<?php
declare(strict_types=1);

namespace App\Policy\Controller;

class WorldsControllerPolicy
    extends AppControllerPolicy
{
    // GET /worlds
    public function index(): bool
    {
        return $this->hasAuth('player');
    }

    // PUT /worlds
    public function add(): bool
    {
        return $this->hasAuth('infobalie');
    }

    // GET /worlds/:id
    public function view(): bool
    {
        return $this->index();
    }

    // PUT /worlds/:id
    public function edit(): bool
    {
        return $this->add();
    }

    // DELETE /worlds/:id
    public function delete(): bool
    {
        return $this->add();
    }
}
