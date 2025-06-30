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
        $rules->addDelete([$this, 'ruleNoCharacters']);

        return $rules;
    }

    public function ruleNoCharacters(EntityInterface $entity, array $options): bool
    {
        $query = $this->CharactersSkills->find();
        $query->where(['skill_id' => $entity->get('id')]);

        if ($query->count() > 0) {
            $entity->setError('characters', $this->consistencyError);

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
