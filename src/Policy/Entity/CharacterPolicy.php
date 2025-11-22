<?php
declare(strict_types=1);

namespace App\Policy\Entity;

use App\Model\Entity\Character;
use App\Model\Entity\Entity;
use App\Model\Enum\Authorization;
use App\Model\Enum\CharacterStatus;
use Authorization\IdentityInterface as User;

class CharacterPolicy extends EntityPolicy
{
    public function __construct()
    {
        parent::__construct();

        $this->editFieldAuth('xp', Authorization::Referee);
        $this->editFieldAuth('status', Authorization::Referee);

        $this->showFieldAuth('notes', Authorization::ReadOnly);
        $this->showFieldAuth('referee_notes', Authorization::ReadOnly);
    }

    public function canAdd(User $identity, Character $obj): bool
    {
        return $this->hasAuthObj($obj, Authorization::Player);
    }

    public function canDelete(User $identity, Character $obj): bool
    {
        $allowed = $this->hasAuthObj($obj, Authorization::Super);
        if (!$allowed && $obj->get('status') === CharacterStatus::Concept) {
            $allowed = $this->hasAuthObj($obj, Authorization::Referee, Authorization::Owner);
        }

        return $allowed;
    }

    public function canEdit(User $identity, Character $obj): bool
    {
        $allowed = $this->hasAuthObj($obj, Authorization::Referee);
        if (!$allowed && $obj->get('status') === CharacterStatus::Concept) {
            $allowed = $this->hasAuthObj($obj, Authorization::Owner);
        }

        return $allowed;
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
