<?php
namespace App\Model\Entity;

use App\AuthState;
use Cake\ORM\Entity;

class Item
	extends Entity
{

	protected $_hidden = [ 'character_id' ];

	protected $_virtual = [ 'plin', 'chin' ];

	public function __construct($properties = [], $options = [])
	{
		parent::__construct($properties, $options);

		if(!AuthState::hasRole('referee')) {
			$this->_hidden[] = 'cs_text';
			$this->_hidden[] = 'attributes';
		}
	}

	protected function _getDisplayName() {
		return $this->_properties['id']
			. ': ' . $this->_properties['name'];
	}

	protected function _getPlin()
	{
		return @$this->character->player_id;
	}

	protected function _getChin()
	{
		return @$this->character->chin;
	}
}
