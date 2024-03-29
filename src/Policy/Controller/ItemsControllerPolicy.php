<?php
declare(strict_types=1);

namespace App\Policy\Controller;

class ItemsControllerPolicy
    extends AppControllerPolicy
{
    // GET /items
    public function index(): bool
    {
        return $this->hasAuth('player');
    }

    // PUT /items
    public function add(): bool
    {
        return $this->hasAuth('referee');
    }

    // GET /items/:itin
    public function view(): bool
    {
        return $this->index();
    }

    // PUT /items/:itin
    public function edit(): bool
    {
        return $this->add();
    }

    // DELETE /items/:itin
    public function delete(): bool
    {
        return $this->hasAuth('super');
    }

    // POST /items/:itin/print
    public function queue(): bool
    {
        return $this->hasAuth('referee');
    }

    // GET /character/:plin/:chin/items
    public function charactersIndex(): bool
    {
        return $this->hasAuth('player');
    }
}
