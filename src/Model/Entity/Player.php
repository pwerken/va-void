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

	protected $_virtual = [ 'full_name' ];

	public function _setPassword($password)
	{
		if(empty($password))
			return NULL;

		return (new DefaultPasswordHasher)->hash($password);
	}

	public static function labelPassword($key = null)
	{
		return isset($key);
	}

	public static function labelsRoles($keys = false)
	{
		static $data = null;
		if(is_null($data))
			$data = [ 'Player'      => __('Player')
					, 'Referee'     => __('Referee')
					, 'Infobalie'   => __('Infobalie')
					, 'Super'       => __('Super')
					];
		return ($keys ? array_keys($data) : $data);
	}
	public static function labelRole($key = null)
	{
		$data = self::labelsRoles();
		if(isset($data[$key]))
			return $data[$key];
		return null;
	}

	public static function labelsGenders($keys = false)
	{
		static $data = null;
		if(is_null($data))
			$data = [ 'F' => __('Female')
					, 'M' => __('Male')
					];
		return ($keys ? array_keys($data) : $data);
	}
	public static function labelGender($key = null)
	{
		$data = self::labelsGenders();
		if(isset($data[$key]))
			return $data[$key];
		return null;
	}

	protected function _getFullName()
	{
		return $this->first_name
				. (empty($this->insertion) ? '' : ' ' . $this->insertion)
				. ' ' . $this->last_name;
	}

}
