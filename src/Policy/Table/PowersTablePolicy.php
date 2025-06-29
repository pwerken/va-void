<?php
declare(strict_types=1);

namespace App\Policy\Table;

use App\Policy\Policy;
use Authorization\IdentityInterface as User;
use Cake\ORM\Query;

class PowersTablePolicy extends Policy
{
    public function scopeIndex(User $identity, Query $query): void
    {
        if (!$this->hasAuth('read-only')) {
            $query->where(['Characters.player_id' => $this->getPlin()])
              ->leftJoin(
                  ['CharactersPowers' => 'characters_powers'],
                  ['CharactersPowers.power_id = Powers.id'],
              )
              ->leftJoin(
                  ['Characters' => 'characters'],
                  ['Characters.id = CharactersPowers.character_id'],
              );
        }
    }
}
