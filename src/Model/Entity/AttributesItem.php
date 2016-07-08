<?php
namespace App\Model\Entity;

class AttributesItem
	extends AppEntity
{

	protected $_showAuth =
			[ 'attribute_id'    => 'super'
			, 'item_id'         => 'super'
			];

	protected $_compact = [ 'attribute', 'item' ];

	public function getUrl($parent = null)
	{
		return $this->getRelationUrl('item', 'attribute', $parent);
	}

}
