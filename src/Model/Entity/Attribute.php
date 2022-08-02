<?php
namespace App\Model\Entity;

class Attribute
    extends AppEntity
{

    public function __construct($properties = [], $options = [])
    {
        parent::__construct($properties, $options);

        $this->setCompact(['code'], true);
    }
}
