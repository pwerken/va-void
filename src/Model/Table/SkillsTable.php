<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\Datasource\EntityInterface;
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

        $rules->add([$this, 'ruleManaConsistency']);

        return $rules;
    }

    public function ruleManaConsistency(EntityInterface $entity, array $options): bool
    {
        $amount = $entity->get('mana_amount');
        $manatype = $entity->get('manatype_id');

        if ($amount > 0 && is_null($manatype)) {
            $entity->setError('manatype_id', ['consistency' => 'Missing value']);

            return false;
        }

        if ($amount == 0 && $manatype) {
            $entity->setError('mana_amount', ['consistency' => 'Missing value']);

            return false;
        }

        return true;
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
