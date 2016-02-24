<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

class Character extends Entity {

	protected $_hidden =
		[ 'id'
		];

	protected function _getDisplayName() {
		return $this->_properties['player_id']
			. '-' . $this->_properties['chin']
			. ': ' . $this->_properties['name'];
	}
}
