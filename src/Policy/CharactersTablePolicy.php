<?php
declare(strict_types=1);

namespace App\Policy;

use Authorization\IdentityInterface;
use Cake\ORM\Query;

class CharactersTablePolicy
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

    public function scopeBelievesIndex(IdentityInterface $identity, Query $query)
    {
        $this->scopeIndex($identity, $query);
    }

    public function scopeFactionsIndex(IdentityInterface $identity, Query $query)
    {
        $this->scopeIndex($identity, $query);
    }

    public function scopeGroupsIndex(IdentityInterface $identity, Query $query)
    {
        $this->scopeIndex($identity, $query);
    }

    public function scopePlayersIndex(IdentityInterface $identity, Query $query)
    {
        $this->scopeIndex($identity, $query);
    }

    public function scopeWorldsIndex(IdentityInterface $identity, Query $query)
    {
        $this->scopeIndex($identity, $query);
    }
}
