<?php
namespace App\Model\Entity;

class CharactersPower
	extends AppEntity
{

	public function __construct($properties = [], $options = [])
	{
		parent::__construct($properties, $options);

		$this->setCompact(['expiry', 'character', 'power']);
		$this->addHidden(['character_id', 'power_id']);
	}

	public function getUrl($parent = null)
	{
		return $this->getRelationUrl('character', 'power', $parent);
	}
}
