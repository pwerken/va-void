<?php
eeclare(strict_types=1);

namespace App\Policy;

use Authorization\IdentityInterface;
use Cake\ORM\Query;

class ConditionsTablePolicy
    extends AppPolicy
{

    public function scopeIndex(IdentityInterface $identity, Query $query)
    {
        $this->identity = $identity;
        if (!$this->hasAuth('read-only')) {
            $plin = $identity->getIdentifier();
            $query->where(['Characters.player_id' => $plin])
                   ->leftJoin(['CharactersConditions' => 'characters_conditions'],
                           ['CharactersConditions.condition_id = Conditions.id'])
                   ->leftJoin(['Characters' => 'characters'],
                           ['Characters.id = CharactersConditions.character_id']);
        }
    }

    public function scopeCharactersIndex(IdentityInterface $identity, Query $query)
    {
        $this->scopeIndex($identity, $query);
    }

    public function scopeConditionsIndex(IdentityInterface $identity, Query $query)
    {
        $this->scopeIndex($identity, $query);
    }
}
