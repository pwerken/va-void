<?php
declare(strict_types=1);

namespace App\Policy\Entity;

use Authorization\IdentityInterface as User;

use App\Model\Entity\Manatype;

class ManatypePolicy
    extends AppEntityPolicy
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
