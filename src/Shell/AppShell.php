<?php
namespace App\Shell;

use App\Utility\AuthState;
use Cake\Console\Shell;

class AppShell extends Shell
{

	public function initialize()
	{
		AuthState::setAuth($this, -2);
	}

	public function user($field)
	{
		switch($field) {
		case 'id':
			return -2;
		case 'role':
			return 'Super';
		default:
			return NULL;
		}
	}

}
