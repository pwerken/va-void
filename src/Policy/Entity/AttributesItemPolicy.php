<?php
declare(strict_types=1);

namespace App\Policy\Entity;

use App\Model\Entity\AttributesItem;
use Authorization\IdentityInterface as User;

class AttributesItemPolicy extends EntityPolicy
{
    public function canItemsAdd(User $identity, AttributesItem $obj): bool
    {
        return $this->hasAuth(['referee'], $obj);
    }

    public function canItemsView(User $identity, AttributesItem $obj): bool
    {
        return $this->hasAuth(['read-only'], $obj);
    }

    public function canItemsEdit(User $identity, AttributesItem $obj): bool
    {
        return $this->canItemsAdd($identity, $obj);
    }

    public function canItemsDelete(User $identity, AttributesItem $obj): bool
    {
        return $this->canItemsAdd($identity, $obj);
    }
}
