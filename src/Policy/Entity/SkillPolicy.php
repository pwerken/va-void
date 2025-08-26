<?php
declare(strict_types=1);

namespace App\Policy\Entity;

use App\Model\Entity\Skill;
use App\Model\Enum\Authorization;
use Authorization\IdentityInterface as User;

class SkillPolicy extends EntityPolicy
{
    public function canAdd(User $identity, Skill $obj): bool
    {
        return $this->hasAuthObj($obj, Authorization::Super);
    }

    public function canView(User $identity, Skill $obj): bool
    {
        return $this->hasAuthObj($obj, Authorization::Player);
    }

    public function canEdit(User $identity, Skill $obj): bool
    {
        return $this->canAdd($identity, $obj);
    }

    public function canDelete(User $identity, Skill $obj): bool
    {
        return $this->canAdd($identity, $obj);
    }
}
