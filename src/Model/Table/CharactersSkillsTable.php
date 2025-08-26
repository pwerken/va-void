<?php
declare(strict_types=1);

namespace App\Model\Table;

use ArrayObject;
use Cake\Datasource\EntityInterface;
use Cake\Event\EventInterface;
use Cake\ORM\RulesChecker;

/**
 * @property \App\Model\Table\CharactersTable $Characters;
 * @property \App\Model\Table\SkillsTable $Skills;
 */
class CharactersSkillsTable extends Table
{
    protected bool $allowSetPrimaryOnCreate = true;

    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setPrimaryKey(['character_id', 'skill_id']);

        $this->belongsTo('Characters');
        $this->belongsTo('Skills');
    }

    public function afterDelete(EventInterface $event, EntityInterface $entity, ArrayObject $options): void
    {
        $this->touchEntity('Characters', $entity->get('character_id'));
    }

    public function afterSave(EventInterface $event, EntityInterface $entity, ArrayObject $options): void
    {
        $this->touchEntity('Characters', $entity->get('character_id'));
    }

    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules = parent::buildRules($rules);

        $rules->addCreate($rules->isUnique(['skill_id', 'character_id']));
        $rules->addCreate([$this, 'ruleDisallowDeprecated']);
        $rules->addCreate([$this, 'ruleLimitTimesToMax']);

        $rules->addUpdate([$this, 'ruleLimitTimesToMax']);

        $rules->add($rules->existsIn('character_id', 'Characters'));
        $rules->add($rules->existsIn('skill_id', 'Skills'));

        return $rules;
    }

    public function ruleDisallowDeprecated(EntityInterface $entity, array $options): bool
    {
        $skill = $this->Skills->getMaybe($entity->get('skill_id'));
        if ($skill?->get('deprecated')) {
            $entity->setError('skill_id', ['deprecated' => 'Skill is deprecated']);

            return false;
        }

        return true;
    }

    public function ruleLimitTimesToMax(EntityInterface $entity, array $options): bool
    {
        $skill = $this->Skills->getMaybe($entity->get('skill_id'));
        if (!is_null($skill) && $entity->get('times') > $skill->get('times_max')) {
            $entity->setError('times', ['limit' => 'Value exceeds skills times_max']);

            return false;
        }

        return true;
    }

    protected function contain(): array
    {
        return ['Characters', 'Skills.Manatypes'];
    }
}
