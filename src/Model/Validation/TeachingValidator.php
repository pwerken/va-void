<?php
declare(strict_types=1);

namespace App\Model\Validation;

class TeachingValidator extends Validator
{
    public function __construct()
    {
        parent::__construct();

        $this->requirePresence('student_id', 'create');
        $this->requirePresence('teacher_id', 'create');
        $this->requirePresence('skill_id', 'create');

        $this->nonNegativeInteger('student_id');
        $this->nonNegativeInteger('teacher_id');
        $this->nonNegativeInteger('skill_id');
    }
}
