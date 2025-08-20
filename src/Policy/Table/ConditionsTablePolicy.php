<?php
declare(strict_types=1);

namespace App\Policy\Table;

use App\Model\Enum\Authorization;
use App\Policy\Policy;
use Authorization\IdentityInterface as User;
use Cake\ORM\Query;

class ConditionsTablePolicy extends Policy
{
    public function scopeIndex(User $identity, Query $query): void
    {
        if (!$this->hasAuth(Authorization::ReadOnly)) {
            $query->where(['Characters.player_id' => $this->getPlin()])
              ->leftJoin(
                  ['CharactersConditions' => 'characters_conditions'],
                  ['CharactersConditions.condition_id = Conditions.id'],
              )
              ->leftJoin(
                  ['Characters' => 'characters'],
                  ['Characters.id = CharactersConditions.character_id'],
              );
        }
    }
}
