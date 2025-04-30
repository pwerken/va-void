<?php
declare(strict_types=1);

namespace App\Policy\Table;

use App\Policy\Policy;
use Authorization\IdentityInterface as User;
use Cake\ORM\Query;

class PlayersTablePolicy extends Policy
{
    public function scopeIndex(User $identity, Query $query): void
    {
        $this->setIdentity($identity);
        if ($this->hasAuth('read-only')) {
            return;
        }

        $query->where(['Players.id' => $identity->getIdentifier()]);
    }
}
