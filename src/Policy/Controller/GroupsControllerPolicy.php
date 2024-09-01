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
}
