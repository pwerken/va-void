<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

class Player extends Entity {

	protected $_virtual =
		[ 'full_name' ];

	protected $_hidden =
		[ 'password' ];

	protected function _getFullName() {
		return $this->_properties['first_name'] . ' '
				. (empty($this->_properties['insertion'])
					? '' : $this->_properties['insertion'] . ' ')
				.  $this->_properties['last_name'];
	}

	public static function labelsAccountTypes($keys = false) {
		static $data = null;
		if(is_null($data))
			$data = [ 'P' => __('Participant')
					, 'R' => __('Referee')
					, 'I' => __('Infobalie')
					, 'S' => __('Super')
					];
		return ($keys ? array_keys($data) : $data);
	}
	public static function labelAccountType($key = null) {
		$data = self::labelsAccountTypes();
		if(isset($data[$key]))
			return $data[$key];
		return null;
	}

	public static function labelsGenders($keys = false) {
		static $data = null;
		if(is_null($data))
			$data = [ 'F' => __('Female')
					, 'M' => __('Male')
					];
		return ($keys ? array_keys($data) : $data);
	}
	public static function labelGender($key = null) {
		$data = self::labelsGenders();
		if(isset($data[$key]))
			return $data[$key];
		return null;
	}
}
