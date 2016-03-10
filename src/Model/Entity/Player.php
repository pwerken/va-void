<?php
namespace App\Model\Entity;

use App\AuthState;
use Cake\Auth\DefaultPasswordHasher;
use Cake\ORM\Entity;

class Player
	extends Entity
{

	protected $_accessible =
		[ 'password' => false
		, '*' => true
		];

	protected $_virtual =
		[ 'full_name' ];

	public function __construct($properties = [], $options = [])
	{
		parent::__construct($properties, $options);

		if(AuthState::hasRole('user') || AuthState::hasRole('super')) {
			$this->accessible('password', true);
		}
		if(!AuthState::hasRole('infobalie')) {
			$this->hiddenProperties(['password']);
		}
	}

	protected function _getFullName()
	{
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
			$data = [ 'Player'      => __('Player')
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
