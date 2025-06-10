<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class TeachingsFixture extends TestFixture
{
    public function init(): void
    {
        $this->records = [
            [
                'student_id' => 1,
                'teacher_id' => 2,
                'skill_id' => 1,
                'modified' => null,
                'modifier_id' => null,
            ],
            [
                'student_id' => 2,
                'teacher_id' => 1,
                'skill_id' => 1,
                'modified' => null,
                'modifier_id' => null,
            ],
        ];
        parent::init();
    }
}
