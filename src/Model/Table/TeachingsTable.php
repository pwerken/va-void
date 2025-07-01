<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\RulesChecker;

class TeachingsTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setPrimaryKey('student_id');

        $this->hasOne('Teacher', [
            'className' => 'Characters',
            'bindingKey' => 'teacher_id',
            'foreignKey' => 'id',
            'propertyName' => 'teacher',
        ]);
        $this->hasOne('Student', [
            'className' => 'Characters',
            'bindingKey' => 'student_id',
            'foreignKey' => 'id',
            'propertyName' => 'student',
        ]);
        $this->hasOne('Skill', [
            'className' => 'Skills',
            'bindingKey' => 'skill_id',
            'foreignKey' => 'id',
            'propertyName' => 'skill',
        ]);
    }

    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->existsIn('student_id', 'Student'));
        $rules->add($rules->existsIn('teacher_id', 'Teacher'));
        $rules->add($rules->existsIn('skill_id', 'Skills'));

        return $rules;
    }

    public function findStudent(int $plin, int $chin): mixed
    {
        $query = $this->find('all', contain: ['Student']);
        $query->select(['Teachings.student_id']);
        $query->where(['Student.player_id =' => $plin, 'Student.chin = ' => $chin]);
        $query->limit(1);
        $query->enableHydration(false);

        return $query->first();
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
