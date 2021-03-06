<?php
namespace App\Model\Entity;

class CharactersSpell
	extends AppEntity
{

	public function __construct($properties = [], $options = [])
	{
		parent::__construct($properties, $options);

		$this->setCompact(['level', 'character', 'spell']);
		$this->addHidden(['character_id', 'spell_id']);
	}

	public function getUrl($parent = null)
	{
		return $this->getRelationUrl('character', 'spell', $parent);
	}
}
