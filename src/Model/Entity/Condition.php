<?php
namespace App\Model\Entity;

use App\AuthState;
use Cake\ORM\Entity;

class Condition
	extends Entity
{

	public function __construct($properties = [], $options = [])
	{
		parent::__construct($properties, $options);

		if(!AuthState::hasRole('referee')) {
			$this->_hidden[] = 'cs_text';
			$this->_hidden[] = 'characters';
		}
	}

	protected function _getDisplayName() {
		return $this->_properties['id']
			. ': ' . $this->_properties['name'];
	}

}
