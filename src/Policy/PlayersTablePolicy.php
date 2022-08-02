<?php
declare(strict_types=1);

namespace App\Policy;

use Authorization\IdentityInterface;
use Cake\ORM\Query;

class PlayersTablePolicy
    extends AppPolicy
{

    public function scopeIndex(IdentityInterface $identity, Query $query)
    {
        $this->identity = $identity;
        if (!$this->hasAuth('read-only')) {
            $plin = $identity->getIdentifier();
            $query->where(["Players.id = $plin"]);
        }
    }
}
