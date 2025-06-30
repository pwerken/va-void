<?php
declare(strict_types=1);

namespace App\Model\Entity;

class CharactersSkill extends Entity
{
    public function __construct(array $properties = [], array $options = [])
    {
        parent::__construct($properties, $options);

        $this->setCompact(['times', 'character', 'skill']);
        $this->setHidden(['character_id', 'skill_id'], true);
    }

    public function getUrl(): string
    {
        return $this->getRelationUrl('character', 'skill');
    }

    public function printableName(Skill $skill): string
    {
        $name = $skill->get('name');
        $times = $this->get('times');
        if ($times > 1) {
            $name .= ' x' . $times;
        }
        if ($skill->get('loresheet') && $skill->get('blanks')) {
            $name .= ' (lore & blanks)';
        } elseif ($skill->get('loresheet')) {
            $name .= ' (lore)';
        } elseif ($skill->get('blanks')) {
            $name .= ' (blanks)';
        }
        $name .= ' (' . ($times * $skill->get('cost')) . ')';

        return $name;
    }
}
