<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\Validation\Validator;

class TeachingsTable
    extends AppTable
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setPrimaryKey('student_id');

        $this->belongsTo('Teacher', ['className' => 'Characters'])
            ->setForeignKey('teacher_id')->setProperty('teacher');
        $this->belongsTo('Skills');
        $this->belongsTo('Student', ['className' => 'Characters'])
            ->setForeignKey('student_id')->setProperty('student');
        $this->belongsTo('Started', ['className' => 'Events'])
            ->setForeignKey('started_id')->setProperty('started_object');
        $this->belongsTo('Updated', ['className' => 'Events'])
            ->setForeignKey('updated_id')->setProperty('updated_object');
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator->allowEmpty('id', 'create');
        $validator->notEmpty('student_id');
        $validator->notEmpty('teacher_id');
        $validator->notEmpty('skill_id');
        $validator->notEmpty('xp');
        $validator->notEmpty('started_id');
        $validator->notEmpty('updated_id');

        // regex for xp validation
        $xp_regex = '/^[0-9]*(?:[.,](?:[05]|[27]5)0*)?$/';

        $validator->add('student_id', 'valid', ['rule' => 'numeric']);
        $validator->add('teacher_id', 'valid', ['rule' => 'numeric']);
        $validator->add('xp', 'valid', ['rule' => ['custom', $xp_regex]]);
        $validator->add('skill_id', 'valid', ['rule' => 'numeric']);
        $validator->add('started_id', 'valid', ['rule' => 'numeric']);
        $validator->add('updated_id', 'valid', ['rule' => 'numeric']);

        $validator->requirePresence('student_id', 'create');
        $validator->requirePresence('teacher_id', 'create');
        $validator->requirePresence('skill_id', 'create');
        $validator->requirePresence('started_id', 'create');
        $validator->requirePresence('updated_id', 'create');

        return $validator;
    }

    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->existsIn('student_id', 'Student'));
        $rules->add($rules->existsIn('teacher_id', 'Teacher'));
        $rules->add($rules->existsIn('skill_id',   'Skills'));
        $rules->add($rules->existsIn('started_id', 'Started'));
        $rules->add($rules->existsIn('updated_id', 'Updated'));

        return $rules;
    }

    public function findStudent($plin, $chin)
    {
        $query = $this->find('all', ['contain' => ['Student']]);
        $query->select(['Teachings.student_id']);
        $query->where(['Student.player_id =' => $plin, 'Student.chin = ' => $chin]);
        $query->limit(1);
        $query->enableHydration(false);
        return $query->first();
    }

    protected function contain(): array
    {
        return ['Student', 'Teacher', 'Skills', 'Started', 'Updated'];
    }

    protected function orderBy(): array
    {
        return ['student_id' => 'ASC'];
    }
}
