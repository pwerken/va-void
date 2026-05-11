<?php
declare(strict_types=1);

namespace App\Policy\Entity;

use App\Model\Entity\CharactersSkill;
use App\Model\Entity\Entity;
use App\Model\Enum\Authorization;
use App\Model\Enum\CharacterStatus;
use Authorization\IdentityInterface as User;
use RuntimeException;

class CharactersSkillPolicy extends EntityPolicy
{
    public function canAdd(User $identity, CharactersSkill $obj): bool
    {
        return $this->hasAuthObj($obj, Authorization::Player);
    }

    public function canView(User $identity, CharactersSkill $obj): bool
    {
        throw new RuntimeException('perform authorization check on character');
    }

    public function canEdit(User $identity, CharactersSkill $obj): bool
    {
        $allowed = $this->hasAuthObj($obj, Authorization::Referee);
        if (!$allowed && $obj->get('character')->get('status') === CharacterStatus::Concept) {
            $allowed = $this->hasAuthObj($obj, Authorization::Owner);
        }

        return $allowed;
    }

    public function canDelete(User $identity, CharactersSkill $obj): bool
    {
        return $this->canEdit($identity, $obj);
    }

    protected function hasRoleUser(int $plin, ?Entity $obj): bool
    {
        return $obj?->get('character')?->get('plin') == $plin;
    }
}
