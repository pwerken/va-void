<?php
namespace App\Model\Entity;

class Item extends JsonEntity {

	protected $_accessible =
		[ 'name' => true
		, 'description' => true
		, 'player_text' => true
		, 'cs_text' => true
		, 'character_id' => true
		, 'expiry' => true
		, 'character_id' => true
		, 'character' => true
		, 'attributes' => true
		];

	protected $_hidden =
		[ 'character_id', 'cs_text' ];

	protected $_json_aliases =
		[ 'id' => 'itin' ];

	protected $_json_short =
		[ 'id', 'name', 'expiry', 'character' ];

	protected function _getDisplayName() {
		return $this->_properties['id']
			. ': ' . $this->_properties['name'];
	}
}
