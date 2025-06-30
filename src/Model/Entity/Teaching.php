<?php
declare(strict_types=1);

namespace App\Model\Entity;

class Teaching extends Entity
{
    public function __construct(array $properties = [], array $options = [])
    {
        parent::__construct($properties, $options);

        $this->setCompact(['student', 'teacher', 'skill']);
        $this->setVirtual(['student', 'teacher', 'skill']);
        $this->setHidden(['student_id', 'teacher_id', 'skill_id'], true);
    }

    public function getUrl(): string
    {
        return 'FIXME'; #$this->student?->getUrl() . '/teacher';
    }
}
