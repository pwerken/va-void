<?php
declare(strict_types=1);

namespace App\Model\Entity;

class Imbue extends Entity
{
    public function __construct(array $properties = [], array $options = [])
    {
        parent::__construct($properties, $options);

        $this->setCompact(['id', 'name', 'cost', 'times_max', 'deprecated'], true);
    }
}
