<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\RulesChecker;

/**
 * @property \App\Model\Table\CharactersSkillsTable $CharactersSkills;
 */
class SkillsTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->belongsTo('Manatypes');

        $this->belongsToManyThrough('Characters', 'CharactersSkills');
    }

    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules = parent::buildRules($rules);

        $rules->addDelete([$this, 'ruleNoAssociation'], ['characters']);

        return $rules;
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
