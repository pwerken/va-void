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
}
