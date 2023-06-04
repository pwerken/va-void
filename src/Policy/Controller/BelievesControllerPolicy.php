<?php
declare(strict_types=1);

namespace App\Policy\Controller;

class BelievesControllerPolicy
    extends AppControllerPolicy
{
    // GET /believes
    public function index(): bool
    {
        return $this->hasAuth('player');
    }

    // PUT /believes
    public function add(): bool
    {
        return $this->hasAuth('infobalie');
    }

    // GET /believes/:id
    public function view(): bool
    {
        return $this->index();
    }

    // PUT /believes/:id
    public function edit(): bool
    {
        return $this->add();
    }

    // DELETE /believes/:id
    public function delete(): bool
    {
        return $this->add();
    }
}
