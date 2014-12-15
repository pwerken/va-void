<?php
namespace App\Model\Entity;

class Player extends JsonEntity {

	protected $_accessible =
		[ 'account_type' => true
		, 'username' => true
		, 'password' => true
		, 'first_name' => true
		, 'insertion' => true
		, 'last_name' => true
		, 'gender' => true
		, 'date_of_birth' => true
		, 'characters' => true
		];

	protected $_virtual =
		[ 'full_name' ];

	protected $_hidden =
		[ 'password' ];

	protected $_json_aliases =
		[ 'id' => 'plin' ];

	protected $_json_short =
		[ 'id', 'full_name' ];

	protected function _getDisplayName() {
		return $this->_properties['id']
			. ': ' . self::_getFullName();
	}
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
