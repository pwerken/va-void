<?php
declare(strict_types=1);

namespace App\Policy\Entity;

use RuntimeException;
use Authorization\IdentityInterface as User;

use App\Model\Entity\CharactersSpell;

class CharactersSpellPolicy
    extends AppEntityPolicy
{
    public function canAdd(User $identity, CharactersSpell $obj): bool
    {
        return $this->hasAuth(['referee'], $obj);
    }

    public function canView(User $identity, CharactersSpell $obj): bool
    {
        throw new RuntimeException('perform authorization check on character');
    }

    public function canEdit(User $identity, CharactersSpell $obj): bool
    {
        return $this->canAdd($identity, $obj);
    }

    public function canDelete(User $identity, CharactersSpell $obj): bool
    {
        return $this->canAdd($identity, $obj);
    }
}
