<?php
namespace App\Model\Entity;

class CharactersPower
	extends AppEntity
{

	protected $_showAuth =
			[ 'character_id'    => 'super'
			, 'power_id'        => 'super'
			];

	public function getUrl($parent = null)
	{
		return $this->getRelationUrl('character', 'power', $parent);
	}

}
