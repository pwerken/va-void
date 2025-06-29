<?php
declare(strict_types=1);

namespace App\Policy\Table;

use App\Policy\Policy;
use Authorization\IdentityInterface as User;
use Cake\ORM\Query;

class CharactersTablePolicy extends Policy
{
    public function scopeIndex(User $identity, Query $query): void
    {
        if (!$this->hasAuth('read-only')) {
            $query->where(['Characters.player_id' => $this->getPlin()]);
        }
    }

    public function scopeFactionsIndex(User $identity, Query $query): void
    {
        $this->scopeIndex($identity, $query);
    }

    public function scopePlayersIndex(User $identity, Query $query): void
    {
        $this->scopeIndex($identity, $query);
    }
}
