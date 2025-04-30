<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\Datasource\EntityInterface;
use Cake\ORM\RulesChecker;

/**
 * @property \App\Model\Table\CharactersPowersTable $CharactersPowers;
 */
class PowersTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->hasMany('CharactersPowers')->setProperty('characters');
    }

    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->addDelete([$this, 'ruleNoCharacters']);

        return $rules;
    }

    public function ruleNoCharacters(EntityInterface $entity, array $options): bool
    {
        $query = $this->CharactersPowers->find();
        $query->where(['power_id' => $entity->id]);

        if ($query->count() > 0) {
            $entity->setError('characters', $this->consistencyError);

            return false;
        }

        return true;
    }

    protected function contain(): array
    {
        return ['CharactersPowers.Characters'];
    }

    protected function orderBy(): array
    {
        return ['id' => 'ASC'];
    }
}
