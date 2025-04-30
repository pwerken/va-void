<?php
declare(strict_types=1);

namespace App\Policy\Entity;

use App\Model\Entity\Event;
use Authorization\IdentityInterface as User;

class EventPolicy extends EntityPolicy
{
    public function canAdd(User $identity, Event $obj): bool
    {
        return $this->hasAuth(['Super'], $obj);
    }

    public function canView(User $identity, Event $obj): bool
    {
        return $this->hasAuth(['player'], $obj);
    }

    public function canEdit(User $identity, Event $obj): bool
    {
        return $this->canAdd($identity, $obj);
    }

    public function canDelete(User $identity, Event $obj): bool
    {
        return $this->canAdd($identity, $obj);
    }
}
