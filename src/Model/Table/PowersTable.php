<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\RulesChecker;

/**
 * @property \App\Model\Table\CharactersPowersTable $CharactersPowers;
 */
class PowersTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setPrimaryKey('poin');

        $this->belongsToManyThrough('Characters', 'CharactersPowers');
    }

    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules = parent::buildRules($rules);

        $rules->addDelete([$this, 'ruleNoAssociation'], ['characters']);

        return $rules;
    }

    protected function contain(): array
    {
        return ['Characters'];
    }

    protected function orderBy(): array
    {
        return ['poin' => 'ASC'];
    }
}
