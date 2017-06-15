<?php
namespace App\Model\Entity;

class CharactersPower
	extends AppEntity
{

	protected $_hidden = [ 'character_id', 'power_id' ];
	protected $_compact = [ 'expiry', 'character', 'power' ];

	public function getUrl($parent = null)
	{
		return $this->getRelationUrl('character', 'power', $parent);
	}

}
