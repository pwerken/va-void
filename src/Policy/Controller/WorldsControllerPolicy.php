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
}
