<?php
declare(strict_types=1);

namespace App\Model\Entity;

/**
 * @property int                            $student_id
 * @property int                            $teacher_id
 * @property int                            $skill_id
 * @property ?\Cake\I18n\DateTime           $modified
 * @property ?int                           $modifier_id
 *
 * Virtual:
 * @property float                          $progress
 *
 * Relations:
 * @property ?\App\Model\Entity\Character   $student
 * @property ?\App\Model\Entity\Character   $teacher
 * @property ?\App\Model\Entity\Skill       $skill
 */
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
        return $this->student->getUrl() . '/teacher';
    }

    protected function _getProgress(): float
    {
        return $this->student->xp_available;
    }
}
