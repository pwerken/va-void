<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\RulesChecker;

/**
 * @property \App\Model\Table\CharactersTable $Characters;
 */
class FactionsTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->hasMany('Characters');
    }

    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->isUnique(['name']));

        $rules->addDelete([$this, 'ruleNoAssociation'], ['characters']);

        return $rules;
    }

    protected function contain(): array
    {
        return ['Characters'];
    }

    protected function orderBy(): array
    {
        return ['name' => 'ASC'];
    }
}
