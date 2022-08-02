<?php
namespace App\Model\Entity;

class CharactersSpell
    extends AppEntity
{

    public function __construct($properties = [], $options = [])
    {
        parent::__construct($properties, $options);

        $this->setCompact(['level', 'character', 'spell']);
        $this->setHidden(['character_id', 'spell_id'], true);
    }

    public function getUrl($parent = null)
    {
        return $this->getRelationUrl('character', 'spell', $parent);
    }
}
