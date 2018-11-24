<?php
namespace App\Model\Entity;

class CharactersSpell
	extends AppEntity
{

	protected $_hidden = [ 'character_id', 'spell_id' ];

	public function __construct($properties = [], $options = [])
	{
		parent::__construct($properties, $options);

		$this->setCompact(['level', 'character', 'spell']);
	}

	public function getUrl($parent = null)
	{
		return $this->getRelationUrl('character', 'spell', $parent);
	}
}
