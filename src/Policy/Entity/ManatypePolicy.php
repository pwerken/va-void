<?php
declare(strict_types=1);

namespace App\Policy\Entity;

use App\Model\Entity\Manatype;
use Authorization\IdentityInterface as User;

class ManatypePolicy extends EntityPolicy
{
    public function __construct()
    {
        parent::__construct();

        $this->showFieldAuth('name', ['player']);
    }

    public function canView(User $identity, Manatype $obj): bool
    {
        return $this->hasAuth(['player'], $obj);
    }
}
