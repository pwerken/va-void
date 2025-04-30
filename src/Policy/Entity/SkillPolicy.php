<?php
declare(strict_types=1);

namespace App\Policy\Entity;

use App\Model\Entity\Skill;
use Authorization\IdentityInterface as User;

class SkillPolicy extends EntityPolicy
{
    public function canView(User $identity, Skill $obj): bool
    {
        return $this->hasAuth(['player'], $obj);
    }
}
