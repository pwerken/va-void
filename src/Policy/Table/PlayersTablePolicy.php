<?php
declare(strict_types=1);

namespace App\Policy\Table;

use App\Model\Enum\Authorization;
use App\Policy\Policy;
use Authorization\IdentityInterface as User;
use Cake\ORM\Query;

class PlayersTablePolicy extends Policy
{
    public function scopeIndex(User $identity, Query $query): void
    {
        if ($this->hasAuth(Authorization::ReadOnly)) {
            return;
        }

        $query->where(['Players.id' => $this->getPlin()]);
    }
}
