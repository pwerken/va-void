<?php
declare(strict_types=1);

namespace App\Policy\Entity;

use App\Model\Entity\Character;
use App\Model\Entity\Entity;
use App\Model\Enum\Authorization;
use Authorization\IdentityInterface as User;

class CharacterPolicy extends EntityPolicy
{
    public function __construct()
    {
        parent::__construct();

        $this->showFieldAuth('notes', Authorization::ReadOnly);
        $this->showFieldAuth('referee_notes', Authorization::ReadOnly);
    }

    public function canAdd(User $identity, Character $obj): bool
    {
        return $this->hasAuthObj($obj, Authorization::Referee);
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
        return $this->hasAuthObj($obj, Authorization::ReadOnly, Authorization::Owner);
    }

    public function canItemsIndex(User $identity, Character $obj): bool
    {
        return $this->hasAuthObj($obj, Authorization::ReadOnly, Authorization::Owner);
    }

    protected function hasRoleUser(int $plin, ?Entity $obj): bool
    {
        return $obj?->get('plin') == $plin;
    }
}
