<?php
namespace App\Model\Entity;

class CharactersCondition
	extends AppEntity
{

	protected $_hidden = [ 'character_id', 'condition_id' ];
	protected $_compact = [ 'expiry', 'character', 'condition' ];

	public function getUrl($parent = null)
	{
		return $this->getRelationUrl('character', 'condition', $parent);
	}

}
