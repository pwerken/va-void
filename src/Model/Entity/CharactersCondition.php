<?php
namespace App\Model\Entity;

class CharactersCondition
	extends AppEntity
{

	protected $_hidden = [ 'character_id', 'condition_id' ];

	public function __construct($properties = [], $options = [])
	{
		parent::__construct($properties, $options);

		$this->setCompact(['expiry', 'character', 'condition']);
	}

	public function getUrl($parent = null)
	{
		return $this->getRelationUrl('character', 'condition', $parent);
	}
}
