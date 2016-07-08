<?php
namespace App\Model\Entity;

class AttributesItem
	extends AppEntity
{

	protected $_hidden = [ 'attribute_id', 'item_id' ];

	protected $_compact = [ 'attribute', 'item' ];

	public function getUrl($parent = null)
	{
		return $this->getRelationUrl('item', 'attribute', $parent);
	}

}
