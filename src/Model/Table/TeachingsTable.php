<?php
declare(strict_types=1);

namespace App\Model\Table;

use ArrayObject;
use Cake\Datasource\EntityInterface;
use Cake\Event\EventInterface;
use Cake\ORM\RulesChecker;

class TeachingsTable extends Table
{
    protected bool $allowSetPrimaryOnCreate = true;

    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setPrimaryKey('student_id');

        $this->belongsTo('Teacher', [
            'className' => 'Characters',
            'propertyName' => 'teacher',
        ]);
        $this->belongsTo('Student', [
            'className' => 'Characters',
            'propertyName' => 'student',
        ]);
        $this->belongsTo('Skill', [
            'className' => 'Skills',
            'propertyName' => 'skill',
        ]);
    }

    public function afterDelete(EventInterface $event, EntityInterface $entity, ArrayObject $options): void
    {
        $this->touchEntity('Characters', $entity->get('student_id'));
    }

    public function afterSave(EventInterface $event, EntityInterface $entity, ArrayObject $options): void
    {
        $this->touchEntity('Characters', $entity->get('student_id'));
    }

    public function beforeMarshal(EventInterface $event, ArrayObject $data, ArrayObject $options): void
    {
        if (isset($data['plin']) && isset($data['chin'])) {
            $char = $this->fetchTable('Characters')
                        ->findByPlinAndChin($data['plin'], $data['chin'])
                        ->first();
            $data['teacher_id'] = ($char ? $char['id'] : -1);
        }

        parent::beforeMarshal($event, $data, $options);
    }

    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->addCreate($rules->isUnique(['student_id']));

        $rules->add($rules->existsIn('student_id', 'Student'));
        $rules->add($rules->existsIn('teacher_id', 'Teacher'));
        $rules->add($rules->existsIn('skill_id', 'Skill'));

        return $rules;
    }

    protected function contain(): array
    {
        return ['Student', 'Teacher', 'Skill.Manatypes'];
    }

    protected function orderBy(): array
    {
        return ['student_id' => 'ASC'];
    }
}
