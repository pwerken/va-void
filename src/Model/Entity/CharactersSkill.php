<?php
namespace App\Model\Entity;

class CharactersSkill
	extends AppEntity
{

	protected $_hidden = [ 'character_id', 'skill_id' ];

	protected $_compact = [ 'character', 'skill' ];

	public function getUrl($parent = null)
	{
		return $this->getRelationUrl('character', 'skill', $parent);
	}

}
