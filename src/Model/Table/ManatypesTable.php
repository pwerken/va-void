<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;

/**
 * @property \App\Model\Table\SkillsTable $Skills;
 */
class ManatypesTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->hasMany('Skills');
    }

    public function findIndex(SelectQuery $query): SelectQuery
    {
        $query->where(['deprecated' => false]);

        return $query;
    }

    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->addDelete([$this, 'ruleNoAssociation'], ['skills']);

        return $rules;
    }

    protected function orderBy(): array
    {
        return ['name' => 'ASC'];
    }
}
