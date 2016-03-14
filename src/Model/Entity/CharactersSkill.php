<?php
namespace App\Model\Entity;

class CharactersSkill
	extends AppEntity
{

	protected $_showAuth =
			[ 'character_id'    => 'super'
			, 'skill_id'        => 'super'
			];

}
