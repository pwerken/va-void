<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\RulesChecker;

/**
 * @property \App\Model\Table\CharactersConditionsTable $CharactersConditions;
 */
class ConditionsTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setPrimaryKey('coin');

        $this->belongsTo('Manatypes');

        $this->belongsToManyThrough('Characters', 'CharactersConditions');
    }

    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules = parent::buildRules($rules);

        $rules->addDelete([$this, 'ruleNoAssociation'], ['characters']);

        $rules->add($rules->existsIn('manatype_id', 'Manatypes'));
        $rules->add([$this, 'ruleManaConsistency']);

        return $rules;
    }

    protected function contain(): array
    {
        return ['Characters', 'Manatypes'];
    }

    protected function orderBy(): array
    {
        return ['coin' => 'ASC'];
    }
}
