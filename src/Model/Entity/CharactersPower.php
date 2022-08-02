<?php
namespace App\Model\Entity;

class CharactersPower
    extends AppEntity
{

    public function __construct($properties = [], $options = [])
    {
        parent::__construct($properties, $options);

        $this->setCompact(['expiry', 'character', 'power']);
        $this->setHidden(['character_id', 'power_id'], true);
    }

    public function getUrl($parent = null)
    {
        return $this->getRelationUrl('character', 'power', $parent);
    }
}
