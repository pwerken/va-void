<?php
declare(strict_types=1);

namespace App\Policy\Controller;

class SkillsControllerPolicy
    extends AppControllerPolicy
{
    // GET /skills
    public function index(): bool
    {
        return $this->hasAuth('player');
    }

    // PUT /skills
    public function add(): bool
    {
        return false; //$this->hasAuth('super');
    }

    // GET /skills/:id
    public function view(): bool
    {
        return $this->index();
    }

    // PUT /skills/:id
    public function edit(): bool
    {
        return $this->add();
    }

    // DELETE /skills/:id
    public function delete(): bool
    {
        return $this->add();
    }
}
