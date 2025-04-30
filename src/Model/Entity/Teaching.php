<?php
declare(strict_types=1);

namespace App\Model\Entity;

class Teaching extends Entity
{
    public function __construct(array $properties = [], array $options = [])
    {
        parent::__construct($properties, $options);

        $this->setCompact(['student', 'teacher', 'skill', 'xp', 'started', 'updated']);
        $this->setVirtual(['student', 'teacher', 'started', 'updated']);
        $this->setHidden(['student_id', 'teacher_id', 'skill_id'], true);
        $this->setHidden(['started_id', 'started_object'], true);
        $this->setHidden(['updated_id', 'updated_object'], true);
    }

    protected function _getStarted(): string
    {
        return $this->get('started_object')->name;
    }

    protected function _getUpdated(): string
    {
        return $this->get('updated_object')->name;
    }

    public function getUrl(?Entity $fallback = null): string
    {
        $student = $this->student ?? $fallback;

        return $student->getUrl() . '/teacher';
    }

    public function progress(): array
    {
        $txp = $xp = 0;
        for ($i = $this->get('xp') * 2; $i >= 6; $i -= 6) {
            $xp += 2;
            $txp += 2;
        }
        for (; $i >= 2; $i -= 2) {
            $xp += 1;
        }
        $txp += $i;

        return [$txp, $xp];
    }
}
