<?php
declare(strict_types=1);

namespace App\Policy\Entity;

use App\Model\Entity\Lammy;
use App\Model\Enum\Authorization;
use Authorization\IdentityInterface as User;

class LammyPolicy extends EntityPolicy
{
    public function __construct()
    {
        parent::__construct();

        $this->showFieldAuth('characters', Authorization::ReadOnly);
    }

    public function canAdd(User $identity, Lammy $obj): bool
    {
        return $this->hasAuthObj($obj, Authorization::Super);
    }

    public function canDelete(User $identity, Lammy $obj): bool
    {
        return $this->canAdd($identity, $obj);
    }

    public function canEdit(User $identity, Lammy $obj): bool
    {
        return $this->canAdd($identity, $obj);
    }

    public function canView(User $identity, Lammy $obj): bool
    {
        return $this->hasAuthObj($obj, Authorization::ReadOnly);
    }
}
