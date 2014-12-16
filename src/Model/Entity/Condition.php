<?php
namespace App\Model\Entity;

class Condition extends JsonEntity {

	protected $_accessible =
		[ 'name' => true
		, 'player_text' => true
		, 'cs_text' => true
		, 'characters' => true
		, '_joinData' => true
		];

	protected $_hidden =
		[ 'cs_text' ];

	protected $_json_aliases =
		[ 'id' => 'coin' ];

	protected $_json_short =
		[ 'id', 'name' ];

	protected function _getDisplayName() {
		return $this->_properties['id']
			. ': ' . $this->_properties['name'];
	}

}
