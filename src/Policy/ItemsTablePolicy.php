<?php
declare(strict_types=1);

namespace App\Policy;

use Authorization\IdentityInterface;
use Cake\ORM\Query;

class ItemsTablePolicy
    extends AppPolicy
{

    public function scopeIndex(IdentityInterface $identity, Query $query)
    {
        $this->identity = $identity;
        if (!$this->hasAuth('read-only')) {
            $plin = $identity->getIdentifier();
            $query->where(['Characters.player_id' => $plin]);
        }
    }

    public function scopeCharactersIndex(IdentityInterface $identity, Query $query)
    {
        $this->scopeIndex($identity, $query);
    }
}
