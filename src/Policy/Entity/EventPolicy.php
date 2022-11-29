<?php
declare(strict_types=1);

namespace App\Policy\Entity;

use Authorization\IdentityInterface as User;

use App\Model\Entity\Event;

class EventPolicy
    extends AppEntityPolicy
{
    public function canView(User $identity, Event $obj): bool
    {
        return $this->hasAuth(['player'], $obj);
    }
}
