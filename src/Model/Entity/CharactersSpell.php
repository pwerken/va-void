<?php
namespace App\Model\Entity;

class CharactersSpell
	extends AppEntity
{

	protected $_hidden = [ 'character_id', 'spell_id' ];

	protected $_compact = [ 'level', 'character', 'spell' ];

	public function getUrl($parent = null)
	{
		return $this->getRelationUrl('character', 'spell', $parent);
	}

}
