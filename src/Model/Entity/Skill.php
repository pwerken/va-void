<?php
namespace App\Model\Entity;

class Skill
    extends AppEntity
{
    public function __construct($properties = [], $options = [])
    {
        parent::__construct($properties, $options);

        $this->setCompact(['cost', 'times_max'], true);
        $this->setCompact(['mana_amount', 'manatype'], true);
        $this->setCompact(['loresheet', 'blanks'], true);
        $this->setCompact(['deprecated'], true);
    }
}
