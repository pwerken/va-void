<?php
namespace App\Model\Entity;

class CharactersPower
	extends AppEntity
{

	protected $_hidden = [ 'character_id', 'power_id' ];

	public function __construct($properties = [], $options = [])
	{
		parent::__construct($properties, $options);

		$this->setCompact(['expiry', 'character', 'power']);
	}

	public function getUrl($parent = null)
	{
		return $this->getRelationUrl('character', 'power', $parent);
	}
}
