<?php
namespace App\Model\Entity;

class Spell
    extends AppEntity
{

    public function __construct($properties = [], $options = [])
    {
        parent::__construct($properties, $options);

        $this->setCompact(['short'], true);
    }
}
