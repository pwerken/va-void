<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

class Condition extends Entity {

	protected $_hidden =
		[ 'cs_text' ];

	protected function _getDisplayName() {
		return $this->_properties['id']
			. ': ' . $this->_properties['name'];
	}

}
