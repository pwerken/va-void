<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\Datasource\EntityInterface;
use Cake\ORM\RulesChecker;

/**
 * @property \App\Model\Table\CharactersConditionsTable $CharactersConditions;
 */
class ConditionsTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->hasMany('CharactersConditions')->setProperty('characters');
    }

    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->addDelete([$this, 'ruleNoCharacters']);

        return $rules;
    }

    public function ruleNoCharacters(EntityInterface $entity, array $options): bool
    {
        $query = $this->CharactersConditions->find();
        $query->where(['condition_id' => $entity->id]);

        if ($query->count() > 0) {
            $entity->setError('characters', $this->consistencyError);

            return false;
        }

        return true;
    }

    protected function contain(): array
    {
        return ['CharactersConditions.Characters'];
    }

    protected function orderBy(): array
    {
        return ['id' => 'ASC'];
    }
}
