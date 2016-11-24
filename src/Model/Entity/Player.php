<?php
namespace App\Model\Entity;

use Cake\Auth\DefaultPasswordHasher;

class Player
	extends AppEntity
{

	protected $_defaults =
			[ 'role'        => 'Player'
			];

	protected $_editAuth =
			[ 'password'    => ['user', 'super']
			, 'role'        => 'infobalie'
			];

	protected $_showAuth =
			[ 'password'    => ['user', 'infobalie']
			];

	protected $_compact = [ 'id', 'full_name' ];

	protected $_virtual = [ 'full_name' ];

	public function _setPassword($password)
	{
		if(empty($password))
			return NULL;

		return (new DefaultPasswordHasher)->hash($password);
	}

	public static function labelPassword($value = null)
	{
		return isset($value);
	}

	public static function roleValues()
	{
		static $data = null;
		if(is_null($data))
			$data = ['Player', 'Referee', 'Infobalie', 'Super'];
		return $data;
	}
	public static function genderValues()
	{
		static $data = null;
		if(is_null($data))
			$data = ['F', 'M'];
		return $data;
	}

	protected function _getFullName()
	{
		return $this->first_name
				. (empty($this->insertion) ? '' : ' ' . $this->insertion)
				. ' ' . $this->last_name;
	}

}
