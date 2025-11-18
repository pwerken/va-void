<?php
declare(strict_types=1);

namespace App\Model\Table;

use App\Model\Enum\CharacterStatus;
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

    public function beforeSave(EventInterface $event, EntityInterface $entity, ArrayObject $options): void
    {
        $character = $this->Characters->get($entity->get('character_id'));
        if ($character->get('status') === CharacterStatus::Concept) {
            return;
        }

        parent::beforeSave($event, $entity, $options);
    }

    public function afterSave(EventInterface $event, EntityInterface $entity, ArrayObject $options): void
    {
        $this->touchEntity('Characters', $entity->get('character_id'));
    }

    public function beforeDelete(EventInterface $event, EntityInterface $entity, ArrayObject $options): void
    {
        $character = $this->Characters->get($entity->get('character_id'));
        if ($character->get('status') === CharacterStatus::Concept) {
            return;
        }

        parent::beforeDelete($event, $entity, $options);
    }

    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules = parent::buildRules($rules);

        $rules->add($rules->existsIn('character_id', 'Characters'));
        $rules->add($rules->existsIn('skill_id', 'Skills'));

        $rules->addCreate($rules->isUnique(['skill_id', 'character_id']));
        $rules->addCreate([$this, 'ruleDisallowDeprecated']);
        $rules->addCreate([$this, 'ruleLimitTimesToMax']);
        $rules->addCreate([$this, 'ruleLimitConceptCharacter']);

        $rules->addUpdate([$this, 'ruleLimitTimesToMax']);
        $rules->addUpdate([$this, 'ruleLimitConceptCharacter']);

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

    public function ruleLimitConceptCharacter(EntityInterface $entity, array $options): bool
    {
        $character = $this->Characters->get($entity->get('character_id'));
        if ($character->get('status') !== CharacterStatus::Concept) {
            return true;
        }

        $skill = $this->Skills->get($entity->get('skill_id'));
        if ($entity->get('times') > $skill->get('base_max')) {
            $entity->setError('times', ['limit' => 'Skill cannot be taken this many times']);

            return false;
        }

        $xp = $skill->get('cost') * $entity->get('times');
        foreach ($character->get('skills') as $skill) {
            if ($skill->get('id') == $entity->get('skill_id')) {
                continue;
            }
            $xp += $skill->get('cost') * $skill->_joinData->get('times');
        }

        if ($xp > (int)$character->get('xp')) {
            $entity->setError('skill_id', ['limit' => 'XP limit exceeded']);

            return false;
        }

        return true;
    }

    public function ruleLimitTimesToMax(EntityInterface $entity, array $options): bool
    {
        $skill = $this->Skills->getMaybe($entity->get('skill_id'));
        if (!is_null($skill) && $entity->get('times') > $skill->get('times_max')) {
            $entity->setError('times', ['limit' => 'Skill cannot be taken this many times']);

            return false;
        }

        return true;
    }

    protected function contain(): array
    {
        return ['Characters', 'Skills.Manatypes'];
    }
}
