<?php
namespace App\Model\Entity;

class CharactersCondition
	extends AppEntity
{

	protected $_showAuth =
			[ 'character_id'    => 'super'
			, 'condition_id'    => 'super'
			];

	public function getUrl($parent = null)
	{
		return $this->getRelationUrl('character', 'condition', $parent);
	}

}
