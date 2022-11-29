<?php
declare(strict_types=1);

namespace App\Policy\Table;

use Authorization\IdentityInterface as User;
use Cake\ORM\Query;

use App\Policy\AppPolicy;

class CharactersTablePolicy
    extends AppPolicy
{
    public function scopeIndex(User $identity, Query $query): void
    {
        $this->setIdentity($identity);
        if (!$this->hasAuth('read-only')) {
            $plin = $identity->getIdentifier();
            $query->where(['Characters.player_id' => $plin]);
        }
    }

    public function scopeBelievesIndex(User $identity, Query $query): void
    {
        $this->scopeIndex($identity, $query);
    }

    public function scopeFactionsIndex(User $identity, Query $query): void
    {
        $this->scopeIndex($identity, $query);
    }

    public function scopeGroupsIndex(User $identity, Query $query): void
    {
        $this->scopeIndex($identity, $query);
    }

    public function scopePlayersIndex(User $identity, Query $query): void
    {
        $this->scopeIndex($identity, $query);
    }

    public function scopeWorldsIndex(User $identity, Query $query): void
    {
        $this->scopeIndex($identity, $query);
    }
}
