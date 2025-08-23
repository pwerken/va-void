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

        $this->belongsToManyThrough('Characters', 'CharactersConditions');
    }

    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->addDelete([$this, 'ruleNoAssociation'], ['characters']);

        return $rules;
    }

    protected function contain(): array
    {
        return ['Characters'];
    }

    protected function orderBy(): array
    {
        return ['coin' => 'ASC'];
    }
}
