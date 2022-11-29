<?php
declare(strict_types=1);

namespace App\Policy\Entity;

use Authorization\IdentityInterface as User;

use App\Model\Entity\AttributesItem;

class AttributesItemPolicy
    extends AppEntityPolicy
{
    public function canItemsView(User $identity, AttributesItem $obj): bool
    {
        return $this->hasAuth(['read-only'], $obj);
    }
}
