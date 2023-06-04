<?php
declare(strict_types=1);

namespace App\Policy\Controller;

class SpellsControllerPolicy
    extends AppControllerPolicy
{
    // GET /spells
    public function index(): bool
    {
        return $this->hasAuth('player');
    }

    // PUT /spells
    public function add(): bool
    {
        return false; //$this->hasAuth('super');
    }

    // GET /spells/:id
    public function view(): bool
    {
        return $this->index();
    }

    // PUT /spells/:id
    public function edit(): bool
    {
        return $this->add();
    }

    // DELETE /spells
    public function delete(): bool
    {
        return $this->add();
    }
}
