<?php
declare(strict_types=1);

namespace App\Model\Table;

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
