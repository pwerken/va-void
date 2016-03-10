<?php
namespace App;

class AuthState
{
	private static $_auth = NULL;
	private static $_user = NULL;
	private static $_role = 0;

	public static function hasRole($role)
	{
		if(is_null(self::$_auth))
			return false;

		if(strcasecmp($role, 'user') == 0)
			return self::$_user;

		return self::roleToInt($role) <= self::$_role;
	}

	public static function setAuth($auth, $isUser)
	{
		self::$_auth = $auth;
		self::$_user = $isUser;
		self::$_role = self::roleToInt($auth->user('role'));
	}

	private static function roleToInt($role)
	{
		$roles = ['player','referee','infobalie','super'];
		foreach($roles as $key => $value) {
			if(strcasecmp($role, $value) == 0)
				return $key + 1;
		}
		return 0;
	}

}
