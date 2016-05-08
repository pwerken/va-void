<?php
namespace App\Model\Entity;

class CharactersSkill
	extends AppEntity
{

	protected $_showAuth =
			[ 'character_id'    => 'super'
			, 'skill_id'        => 'super'
			];

	public function getUrl($parent = null)
	{
		return $this->getRelationUrl('character', 'skill', $parent);
	}

}
