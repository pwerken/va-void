<?php
declare(strict_types=1);

namespace App\Policy\Controller;

class ConditionsControllerPolicy
    extends AppControllerPolicy
{
    // GET /conditions
    public function index(): bool
    {
        return $this->hasAuth('player');
    }

    // PUT /conditions
    public function add(): bool
    {
        return $this->hasAuth('referee');
    }

    // GET /conditions/:coin
    public function view(): bool
    {
        return $this->index();
    }

    // PUT /conditions/:coin
    public function edit(): bool
    {
        return $this->add();
    }

    // DELETE /conditions/:coin
    public function delete(): bool
    {
        return $this->hasAuth('super');
    }

    // POST /conditions/:coin/print
    public function queue(): bool
    {
        return $this->hasAuth('referee');
    }
}
