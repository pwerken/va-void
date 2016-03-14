<?php
namespace App\Model\Entity;

class CharactersSpell
	extends AppEntity
{

	protected $_showAuth =
			[ 'character_id'    => 'super'
			, 'spell_id'        => 'super'
			];

}
