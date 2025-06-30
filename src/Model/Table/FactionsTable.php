<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\Datasource\EntityInterface;
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

        $rules->addDelete([$this, 'ruleNoCharacters']);

        return $rules;
    }

    public function ruleNoCharacters(EntityInterface $entity, array $options): bool
    {
        $query = $this->Characters->find();
        $query->where(['faction_id' => $entity->get('id')]);

        if ($query->count() > 0) {
            $entity->setError('characters', $this->consistencyError);

            return false;
        }

        return true;
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
