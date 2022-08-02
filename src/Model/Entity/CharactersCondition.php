<?php
namespace App\Model\Entity;

class CharactersCondition
    extends AppEntity
{

    public function __construct($properties = [], $options = [])
    {
        parent::__construct($properties, $options);

        $this->setCompact(['expiry', 'character', 'condition']);
        $this->setHidden(['character_id', 'condition_id'], true);
    }

    public function getUrl($parent = null)
    {
        return $this->getRelationUrl('character', 'condition', $parent);
    }
}
