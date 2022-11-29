<?php
declare(strict_types=1);

namespace App\Policy\Entity;

use Authorization\IdentityInterface as User;

use App\Model\Entity\Spell;

class SpellPolicy
    extends AppEntityPolicy
{
    public function canView(User $identity, Spell $obj): bool
    {
        return $this->hasAuth(['player'], $obj);
    }
}
