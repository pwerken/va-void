<?php
namespace App\Model\Entity;

use App\AuthState;
use Cake\ORM\Entity;

class Belief
	extends Entity
{

	public function __construct($properties = [], $options = [])
	{
		parent::__construct($properties, $options);

		if(!AuthState::hasRole('referee')) {
			$this->_hidden[] = 'characters';
		}
	}

}
