<?php
declare(strict_types=1);

namespace App\Model\Entity;

/**
 * @property int                                $character_id
 * @property int                                $skill_id
 * @property int                                $times
 * @property ?\Cake\I18n\DateTime               $modified
 * @property ?int                               $modifier_id
 *
 * Relations:
 * @property ?\App\Model\Entity\Character       $character
 * @property ?\App\Model\Entity\Skill           $skill
 */
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
        $name = $skill->name;
        $times = $this->times;
        if ($times > 1) {
            $name .= ' x' . $times;
        }
        if ($skill->loresheet && $skill->blanks) {
            $name .= ' (lore & blanks)';
        } elseif ($skill->loresheet) {
            $name .= ' (lore)';
        } elseif ($skill->blanks) {
            $name .= ' (blanks)';
        }
        $name .= ' (' . ($times * $skill->cost) . ')';

        return $name;
    }
}
