<?php
declare(strict_types=1);

namespace App\Policy\Controller;

class GroupsControllerPolicy
    extends AppControllerPolicy
{
    // GET /groups
    public function index(): bool
    {
        return $this->hasAuth('player');
    }

    // PUT /groups
    public function add(): bool
    {
        return $this->hasAuth('infobalie');
    }

    // GET /groups/:id
    public function view(): bool
    {
        return $this->index();
    }

    // PUT /groups/:id
    public function edit(): bool
    {
        return $this->add();
    }

    // DELETE /groups/:id
    public function delete(): bool
    {
        return $this->add();
    }
}
