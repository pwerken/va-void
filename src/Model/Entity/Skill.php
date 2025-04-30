<?php
declare(strict_types=1);

namespace App\Model\Entity;

class Skill extends Entity
{
    public function __construct(array $properties = [], array $options = [])
    {
        parent::__construct($properties, $options);

        $this->setCompact(['cost', 'times_max'], true);
        $this->setCompact(['mana_amount', 'manatype'], true);
        $this->setCompact(['loresheet', 'blanks'], true);
        $this->setCompact(['deprecated'], true);

        $this->setHidden(['manatype_id'], true);
    }
}
