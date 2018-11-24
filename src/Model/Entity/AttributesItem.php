<?php
namespace App\Model\Entity;

class AttributesItem
	extends AppEntity
{

	protected $_hidden = [ 'attribute_id', 'item_id' ];

	public function __construct($properties = [], $options = [])
	{
		parent::__construct($properties, $options);

		$this->setCompact(['attribute', 'item']);
	}

	public function getUrl($parent = null)
	{
		return $this->getRelationUrl('item', 'attribute', $parent);
	}
}
