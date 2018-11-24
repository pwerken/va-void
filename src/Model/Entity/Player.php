<?php
namespace App\Model\Entity;

use Cake\Auth\DefaultPasswordHasher;

class Player
	extends AppEntity
{

	protected $_defaults =
			[ 'role'        => 'Player'
			];

	protected $_compact = [ 'id', 'full_name' ];

	protected $_virtual = [ 'full_name' ];

	public function __construct($properties = [], $options = [])
	{
		parent::__construct($properties, $options);

		$this->editFieldAuth('password', ['user', 'super']);
		$this->editFieldAuth('role', ['infobalie']);

		$this->showFieldAuth('password', ['user', 'infobalie']);
	}

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
			$data = ['Player', 'Read-only', 'Referee', 'Infobalie', 'Super'];
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
		$name = [$this->first_name, $this->insertion, $this->last_name];
		return implode(' ', array_filter($name));
	}

}
