<?php
declare(strict_types=1);

namespace App\Policy\Table;

use Authorization\IdentityInterface as User;
use Cake\ORM\Query;

use App\Policy\AppPolicy;

class PowersTablePolicy
    extends AppPolicy
{
    public function scopeIndex(User $identity, Query $query): void
    {
        $this->setIdentity($identity);
        if (!$this->hasAuth('read-only')) {
            $plin = $identity->getIdentifier();
            $query->where(['Characters.player_id' => $plin])
              ->leftJoin(['CharactersPowers' => 'characters_powers'],
                      ['CharactersPowers.power_id = Powers.id'])
              ->leftJoin(['Characters' => 'characters'],
                      ['Characters.id = CharactersPowers.character_id']);
        }
    }
}
