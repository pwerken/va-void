<?php
declare(strict_types=1);

namespace App\Model\Entity;

class Teaching extends Entity
{
    public function __construct(array $properties = [], array $options = [])
    {
        parent::__construct($properties, $options);

        $this->setCompact(['student', 'teacher', 'skill', 'progress']);
        $this->setVirtual(['student', 'teacher', 'skill', 'progress']);
        $this->setHidden(['student_id', 'teacher_id', 'skill_id'], true);
    }

    public function getUrl(): string
    {
        if ($this->get('student')) {
            return $this->get('student')->getUrl() . '/teacher';
        }

        return '';
    }

    protected function _getProgress(): float
    {
        $student = $this->get('student');
        if (!$student) {
            return 0;
        }

        return $student->get('xp_available');
    }
}
