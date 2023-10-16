<?php
namespace App\Model\Entity;

class CharactersSkill
    extends AppEntity
{

    public function __construct($properties = [], $options = [])
    {
        parent::__construct($properties, $options);

        $this->setCompact(['character', 'skill']);
        $this->setHidden(['character_id', 'skill_id'], true);
    }

    public function getUrl($parent = null)
    {
        return $this->getRelationUrl('character', 'skill', $parent);
    }

    public function printableName()
    {
        $name = $this->skill->name;
        if($this->skill->loresheet && $this->skill->blanks) {
            $name .= ' (lore & blanks)';
        } elseif($this->skill->loresheet) {
            $name .= ' (lore)';
        } elseif($this->skill->blanks) {
            $name .= ' (blanks)';
        }
        $name .= ' (' . $this->skill->cost . ')';
        return $name;
    }
}
