<?php
declare(strict_types=1);

namespace App\Policy\Entity;

use Authorization\IdentityInterface as User;

use App\Model\Entity\AppEntity;
use App\Model\Entity\Character;

class CharacterPolicy
    extends AppEntityPolicy
{
    public function __construct()
    {
        parent::__construct();

        $this->showFieldAuth('notes',         'read-only');
        $this->showFieldAuth('referee_notes', 'read-only');
    }

    public function canAdd(User $identity, Character $obj): bool
    {
        return $this->hasAuth(['referee'], $obj);
    }

    public function canView(User $identity, Character $obj): bool
    {
        return $this->hasAuth(['read-only', 'user'], $obj);
    }

    public function canEdit(User $identity, Character $obj): bool
    {
        return $this->canAdd($identity, $obj);
    }

    public function canDelete(User $identity, Character $obj): bool
    {
        return $this->canAdd($identity, $obj);
    }

    protected function hasRoleUser(int $plin, AppEntity $obj): bool
    {
        return $obj->get('player_id') == $plin;
    }
}
