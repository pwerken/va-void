<?php
declare(strict_types=1);

namespace App\Policy\Entity;

use App\Model\Entity\Manatype;
use App\Model\Enum\Authorization;
use Authorization\IdentityInterface as User;

class ManatypePolicy extends EntityPolicy
{
    public function __construct()
    {
        parent::__construct();

        $this->showFieldAuth('name', Authorization::Player);
    }

    public function canAdd(User $identity, Manatype $obj): bool
    {
        return $this->hasAuthObj($obj, Authorization::Super);
    }

    public function canView(User $identity, Manatype $obj): bool
    {
        return $this->hasAuthObj($obj, Authorization::Player);
    }

    public function canEdit(User $identity, Manatype $obj): bool
    {
        return $this->canAdd($identity, $obj);
    }

    public function canDelete(User $identity, Manatype $obj): bool
    {
        return $this->canAdd($identity, $obj);
    }
}
