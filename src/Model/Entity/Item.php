<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

class Item extends Entity {

	protected $_hidden =
		[ 'character_id'
        , 'cs_text'
        ];

	protected function _getDisplayName() {
		return $this->_properties['id']
			. ': ' . $this->_properties['name'];
	}

}
