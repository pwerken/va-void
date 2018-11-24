<?php
namespace App\Utility;

use App\Model\Entity\Player;

class AuthState
{
	private static $_auth = NULL;
	private static $_user = NULL;
	private static $_role = 0;

	public static function initialize($auth, $plin)
	{
		self::$_auth = $auth;
		self::$_user = $auth->user('id') == $plin;
		self::$_role = self::roleToInt($auth->user('role'));
	}

	public static function getId()
	{
		return self::$_auth->user('id');
	}

	public static function getRole()
	{
		return self::$_auth->user('role');
	}

	public static function hasRole($role)
	{
		if(is_null(self::$_auth))
			return false;

		if(strcasecmp($role, 'user') == 0)
			return self::$_user;

		return self::roleToInt($role) <= self::$_role;
	}

	public static function hasAuth($roles)
	{
		foreach($roles as $role) {
			if(self::hasRole($role)) {
				return true;
			}
		}
		return false;
	}

	private static function roleToInt($role)
	{
		foreach(Player::roleValues() as $key => $value) {
			if(strcasecmp($role, $value) == 0)
				return $key + 1;
		}
		return 0;
	}

}
