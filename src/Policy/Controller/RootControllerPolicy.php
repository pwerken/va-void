<?php
declare(strict_types=1);

namespace App\Policy\Controller;

class RootControllerPolicy
    extends AppControllerPolicy
{
    // GET /
    public function root(): bool
    {
        return true;
    }

    // OPTIONS /*
    public function cors(): bool
    {
        return true;
    }
}
