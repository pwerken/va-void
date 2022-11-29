<?php
declare(strict_types=1);

namespace App\Policy\Table;

use Authorization\IdentityInterface as User;
use Cake\ORM\Query;

use App\Policy\AppPolicy;

class PlayersTablePolicy
    extends AppPolicy
{
    public function scopeIndex(User $identity, Query $query): void
    {
        $this->setIdentity($identity);
        if ($this->hasAuth('read-only')) {
            return;
        }

        $query->where(["Players.id" => $identity->getIdentifier()]);
    }
}
