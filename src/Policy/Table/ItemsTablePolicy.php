<?php
declare(strict_types=1);

namespace App\Policy\Table;

use App\Policy\Policy;
use Authorization\IdentityInterface as User;
use Cake\ORM\Query;

class ItemsTablePolicy extends Policy
{
    public function scopeIndex(User $identity, Query $query): void
    {
        $this->setIdentity($identity);
        if (!$this->hasAuth('read-only')) {
            $plin = $identity->getIdentifier();
            $query->where(['Characters.player_id' => $plin]);
        }
    }

    public function scopeCharactersIndex(User $identity, Query $query): void
    {
        $this->scopeIndex($identity, $query);
    }
}
