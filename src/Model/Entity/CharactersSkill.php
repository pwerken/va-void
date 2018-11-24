<?php
namespace App\Model\Entity;

class CharactersSkill
	extends AppEntity
{

	protected $_hidden = [ 'character_id', 'skill_id' ];

	public function __construct($properties = [], $options = [])
	{
		parent::__construct($properties, $options);

		$this->setCompact(['character', 'skill']);
	}

	public function getUrl($parent = null)
	{
		return $this->getRelationUrl('character', 'skill', $parent);
	}

}
