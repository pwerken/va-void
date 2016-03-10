<?php
namespace App\Model\Entity;

use App\AuthState;
use Cake\ORM\Entity;

class Character
	extends Entity
{

	protected $_hidden = [ 'id' ];

	public function __construct($properties = [], $options = [])
	{
		parent::__construct($properties, $options);

		if(AuthState::hasRole('user') || AuthState::hasRole('super')) {
			$this->accessible('password', true);
		}

		if(!AuthState::hasRole('referee')) {
			$this->_hidden[] = 'comments';
		}
	}

	protected function _getDisplayName()
	{
		return $this->_properties['player_id']
			. '-' . $this->_properties['chin']
			. ': ' . $this->_properties['name'];
	}

}
