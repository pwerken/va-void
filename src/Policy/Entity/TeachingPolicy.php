<?php
declare(strict_types=1);

namespace App\Policy\Entity;

use App\Model\Entity\Entity;
use App\Model\Entity\Teaching;
use App\Model\Enum\Authorization;
use Authorization\IdentityInterface as User;

class TeachingPolicy extends EntityPolicy
{
    public function canAdd(User $identity, Teaching $obj): bool
    {
        return $this->hasAuthObj($obj, Authorization::Referee);
    }

    public function canDelete(User $identity, Teaching $obj): bool
    {
        return $this->canAdd($identity, $obj);
    }

    public function canEdit(User $identity, Teaching $obj): bool
    {
        return false;
    }

    public function canView(User $identity, Teaching $obj): bool
    {
        return $this->hasAuthObj($obj, Authorization::ReadOnly, Authorization::Owner);
    }

    protected function hasRoleUser(int $plin, ?Entity $obj): bool
    {
        return $obj?->get('student')->get('plin') == $plin;
    }
}
