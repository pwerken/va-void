<?php
declare(strict_types=1);

namespace App\Model\Entity;

class AttributesItem extends Entity
{
    public function __construct(array $properties = [], array $options = [])
    {
        parent::__construct($properties, $options);

        $this->setCompact(['attribute', 'item']);
        $this->setHidden(['attribute_id', 'item_id'], true);
    }

    public function getUrl(array $parents = []): string
    {
        return $this->getRelationUrl('item', 'attribute', $parents);
    }
}
