<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;

/**
 * @property \App\Model\Table\CharactersSkillsTable $CharactersSkills;
 */
class SkillsTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->belongsToManyThrough('Characters', 'CharactersSkills');

        $this->belongsTo('Manatypes');
    }

    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules = parent::buildRules($rules);

        $rules->addDelete([$this, 'ruleNoAssociation'], ['characters']);

        $rules->add($rules->existsIn('manatype_id', 'Manatypes'));
        $rules->add([$this, 'ruleManaConsistency']);

        return $rules;
    }

    public function findIndex(SelectQuery $query): SelectQuery
    {
        return $this->findWithContain($query);
    }

    protected function contain(): array
    {
        return ['Manatypes'];
    }

    protected function orderBy(): array
    {
        return ['sort_order' => 'ASC', 'name' => 'ASC'];
    }
}
