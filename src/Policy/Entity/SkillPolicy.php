<?php
declare(strict_types=1);

namespace App\Policy\Entity;

use Authorization\IdentityInterface as User;

use App\Model\Entity\Skill;

class SkillPolicy
    extends AppEntityPolicy
{
    public function canView(User $identity, Skill $obj): bool
    {
        return $this->hasAuth(['player'], $obj);
    }
}
