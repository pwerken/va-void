<?php
declare(strict_types=1);

namespace App\Policy\Controller;

class ManatypesControllerPolicy extends ControllerPolicy
{
    // GET /manatypes

    public function index(): bool
    {
        return $this->hasAuth('player');
    }

    // PUT /manatypes

    public function add(): bool
    {
        return false; //$this->hasAuth('super');
    }

    // GET /manatypes/:id

    public function view(): bool
    {
        return $this->index();
    }

    // PUT /manatypes/:id

    public function edit(): bool
    {
        return $this->add();
    }

    // DELETE /manatypes/;id
    public function delete(): bool
    {
        return $this->add();
    }
}
