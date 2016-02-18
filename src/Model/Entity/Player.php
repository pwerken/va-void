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

	public function _setPassword($password) {
		return (new DefaultPasswordHasher)->hash($password);
	}

	public static function labelsRoles($keys = false) {
		static $data = null;
		if(is_null($data))
			$data = [ 'Participant'=> __('Participant')
					, 'Referee'     => __('Referee')
					, 'Infobalie'   => __('Infobalie')
					, 'Super'       => __('Super')
					];
		return ($keys ? array_keys($data) : $data);
	}
	public static function labelRole($key = null) {
		$data = self::labelsRoles();
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
