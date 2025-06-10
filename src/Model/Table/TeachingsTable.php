<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\Validation\Validator;

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

    public function validationDefault(Validator $validator): Validator
    {
        $validator->allowEmpty('id', 'create');
        $validator->notEmpty('student_id');
        $validator->notEmpty('teacher_id');
        $validator->notEmpty('skill_id');
        $validator->notEmpty('xp');

        // regex for xp validation
        $xp_regex = '/^[0-9]*(?:[.,](?:[05]|[27]5)0*)?$/';

        $validator->add('student_id', 'valid', ['rule' => 'numeric']);
        $validator->add('teacher_id', 'valid', ['rule' => 'numeric']);
        $validator->add('xp', 'valid', ['rule' => ['custom', $xp_regex]]);
        $validator->add('skill_id', 'valid', ['rule' => 'numeric']);

        $validator->requirePresence('student_id', 'create');
        $validator->requirePresence('teacher_id', 'create');
        $validator->requirePresence('skill_id', 'create');

        return $validator;
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
