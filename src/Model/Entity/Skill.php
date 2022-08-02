<?php
namespace App\Model\Entity;

class Skill
    extends AppEntity
{

    public function __construct($properties = [], $options = [])
    {
        parent::__construct($properties, $options);

        $this->setCompact(['cost', 'mana_amount', 'manatype'], true);
    }
}
