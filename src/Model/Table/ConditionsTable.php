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

        $this->belongsToManyThrough('Characters', 'CharactersConditions');
    }

    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->addDelete([$this, 'ruleNoCharacters']);

        return $rules;
    }

    public function ruleNoCharacters(EntityInterface $entity, array $options): bool
    {
        $this->loadInto($entity, ['Characters']);

        if (count($entity->get('characters')) > 0) {
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
        return ['id' => 'ASC'];
    }
}
