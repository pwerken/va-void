<?php
namespace App\Model\Entity;

class AttributesItem
	extends AppEntity
{

	public function __construct($properties = [], $options = [])
	{
		parent::__construct($properties, $options);

		$this->setCompact(['attribute', 'item']);
		$this->addHidden(['attribute_id', 'item_id']);
	}

	public function getUrl($parent = null)
	{
		return $this->getRelationUrl('item', 'attribute', $parent);
	}
}
