<?php
declare(strict_types=1);

namespace App\Policy\Entity;

use RuntimeException;
use Authorization\IdentityInterface as User;

use App\Model\Entity\CharactersPower;

class CharactersPowerPolicy
    extends AppEntityPolicy
{
    public function canAdd(User $identity, CharactersPower $obj): bool
    {
        return $this->hasAuth(['referee'], $obj);
    }

    public function canView(User $identity, CharactersPower $obj): bool
    {
        throw new RuntimeException('perform authorization check on character');
    }

    public function canEdit(User $identity, CharactersPower $obj): bool
    {
        return $this->hasAuth(['referee'], $obj);
    }

    public function canDelete(User $identity, CharactersPower $obj): bool
    {
        return $this->hasAuth(['referee'], $obj);
    }
}
