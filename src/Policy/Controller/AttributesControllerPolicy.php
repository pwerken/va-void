<?php
declare(strict_types=1);

namespace App\Policy\Controller;

class AttributesControllerPolicy extends ControllerPolicy
{
    // GET /attributes

    public function index(): bool
    {
        return $this->hasAuth('read-only');
    }

    // PUT /attributes

    public function add(): bool
    {
        return false; //$this->hasAuth('super');
    }

    // GET /attributes/:id

    public function view(): bool
    {
        return $this->index();
    }

    // PUT /attributes/:id

    public function edit(): bool
    {
        return $this->add();
    }

    // DELETE /attributes/;id
    public function delete(): bool
    {
        return $this->add();
    }
}
