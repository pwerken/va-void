<?php
namespace App\Model\Entity;

use App\AuthState;
use Cake\ORM\Entity;

abstract class AppEntity
	extends Entity
{

	protected $_defaults = [ ];
	protected $_editAuth = [ ];
	protected $_showAuth = [ ];

	public function __construct($properties = [], $options = [])
	{
		parent::__construct($properties, $options);

		if($this->isNew()) {
			$this->set($this->_defaults, ['guard' => false]);
		}

		foreach($this->_editAuth as $p => $access) {
			if(is_bool($access)) {
				$this->accessible($p, $access);
				continue;
			}

			if(!is_array($access))
				$access = [$access];

			$this->accessible($p, false);
			foreach($access as $auth) {
				if(AuthState::hasRole($auth)) {
					$this->accessible($p, true);
					break;
				}
			}
		}

		foreach($this->_showAuth as $p => $access) {
			if(!is_array($access))
				$access = [$access];

			$show = false;
			foreach($access as $auth) {
				if(AuthState::hasRole($auth)) {
					$show = true;
					break;
				}
			}
			if(!$show)
				$this->_hidden[] = $p;
		}
	}

}

