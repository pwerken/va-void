<?php
namespace App\Model\Entity;

class AttributesItem
    extends AppEntity
{

    public function __construct($properties = [], $options = [])
    {
        parent::__construct($properties, $options);

        $this->setCompact(['attribute', 'item']);
        $this->setHidden(['attribute_id', 'item_id'], true);
    }

    public function getUrl($parent = null)
    {
        return $this->getRelationUrl('item', 'attribute', $parent);
    }
}
