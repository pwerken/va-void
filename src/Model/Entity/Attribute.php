<?php
declare(strict_types=1);

namespace App\Model\Entity;

class Attribute extends Entity
{
    public function __construct(array $properties = [], array $options = [])
    {
        parent::__construct($properties, $options);

        $this->setCompact(['code'], true);
    }
}
