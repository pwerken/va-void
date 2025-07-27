<?php
declare(strict_types=1);

namespace App\Policy\Entity;

use App\Model\Entity\Character;
use App\Model\Entity\Entity;
use Authorization\IdentityInterface as User;

class CharacterPolicy extends EntityPolicy
{
    public function __construct()
    {
        parent::__construct();

        $this->showFieldAuth('notes', 'read-only');
        $this->showFieldAuth('referee_notes', 'read-only');
    }

    public function canAdd(User $identity, Character $obj): bool
    {
        return $this->hasAuth(['referee'], $obj);
    }

    public function canDelete(User $identity, Character $obj): bool
    {
        return $this->canAdd($identity, $obj);
    }

    public function canEdit(User $identity, Character $obj): bool
    {
        return $this->canAdd($identity, $obj);
    }

    public function canView(User $identity, Character $obj): bool
    {
        return $this->hasAuth(['read-only', 'user'], $obj);
    }

    protected function hasRoleUser(int $plin, ?Entity $obj): bool
    {
        return $obj?->get('player_id') == $plin;
    }
}
