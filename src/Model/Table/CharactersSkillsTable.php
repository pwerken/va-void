<?php
declare(strict_types=1);

namespace App\Model\Table;

use ArrayObject;
use Cake\Datasource\EntityInterface;
use Cake\Event\EventInterface;
use Cake\ORM\RulesChecker;

class CharactersSkillsTable
    extends AppTable
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setPrimaryKey(['character_id', 'skill_id']);

        $this->belongsTo('Characters');
        $this->belongsTo('Skills');
    }

    public function afterDelete(EventInterface $event, EntityInterface $entity, ArrayObject $options): void
    {
        $this->touchEntity('Characters', $entity->character_id);
    }

    public function afterSave(EventInterface $event, EntityInterface $entity, ArrayObject $options): void
    {
        $this->touchEntity('Characters', $entity->character_id);
    }

    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->addCreate($rules->isUnique(['skill_id', 'character_id']));

        $rules->add($rules->existsIn('character_id', 'Characters'));
        $rules->add($rules->existsIn('skill_id', 'Skills'));

        $rules->addCreate([$this, 'disallowDeprecated']);

        return $rules;
    }

    public function disallowDeprecated($entity, $options)
    {
        $skill = $this->Skills->get($entity->skill_id);
        if($skill->deprecated) {
            $entity->setError('skill_id', ['deprecated' => 'Skill is deprecated']);
            return false;
        }

        return true;
    }

    protected function contain(): array
    {
        return ['Characters', 'Skills.Manatypes'];
    }
}
