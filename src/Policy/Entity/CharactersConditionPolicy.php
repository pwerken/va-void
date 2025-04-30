<?php
declare(strict_types=1);

namespace App\Policy\Entity;

use App\Model\Entity\CharactersCondition;
use Authorization\IdentityInterface as User;
use RuntimeException;

class CharactersConditionPolicy extends EntityPolicy
{
    public function canAdd(User $identity, CharactersCondition $obj): bool
    {
        return $this->hasAuth(['referee'], $obj);
    }

    public function canView(User $identity, CharactersCondition $obj): bool
    {
        throw new RuntimeException('perform authorization check on character');
    }

    public function canEdit(User $identity, CharactersCondition $obj): bool
    {
        return $this->canAdd($identity, $obj);
    }

    public function canDelete(User $identity, CharactersCondition $obj): bool
    {
        return $this->canAdd($identity, $obj);
    }
}
