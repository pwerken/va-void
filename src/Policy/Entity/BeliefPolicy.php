<?php
declare(strict_types=1);

namespace App\Policy\Entity;

use Authorization\IdentityInterface as User;

use App\Model\Entity\Belief;

class BeliefPolicy
    extends AppEntityPolicy
{
    public function canAdd(User $identity, Belief $obj): bool
    {
        return $this->hasAuth(['infobalie'], $obj);
    }

    public function canView(User $identity, Belief $obj): bool
    {
        return $this->hasAuth(['player'], $obj);
    }

    public function canEdit(User $identity, Belief $obj): bool
    {
        return $this->canAdd($identity, $obj);
    }

    public function canDelete(User $identity, Belief $obj): bool
    {
        return $this->canAdd($identity, $obj);
    }

    public function canCharactersIndex(User $identity, Belief $obj): bool
    {
        return $this->hasAuth(['read-only'], $obj);
    }
}
