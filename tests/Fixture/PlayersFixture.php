<?php
namespace App\Test\Fixture;

use Cake\Auth\DefaultPasswordHasher;
use Cake\TestSuite\Fixture\TestFixture;

class PlayersFixture
	extends TestFixture
{

	public $import = ['table' => 'players', 'connection' => 'default'];

	public function init()
	{
		$password = (new DefaultPasswordHasher)->hash('password');

		$this->records =
			[	[ 'id' => 1
				, 'role' => 'Player'
				, 'password' => $password
				, 'first_name' => 'Player'
				, 'insertion' => NULL
				, 'last_name' => 'One'
				, 'gender' => NULL
				, 'date_of_birth' => NULL
				, 'modified' => NULL
				, 'modifier_id' => NULL
				]
			,	[ 'id' => 2
				, 'role' => 'Read-only'
				, 'password' => $password
				, 'first_name' => 'Read'
				, 'insertion' => NULL
				, 'last_name' => 'Only'
				, 'gender' => NULL
				, 'date_of_birth' => NULL
				, 'modified' => '2018-01-01 01:00:00'
				, 'modifier_id' => 5
				]
			,	[ 'id' => 3
				, 'role' => 'Referee'
				, 'password' => $password
				, 'first_name' => 'Centrale'
				, 'insertion' => ''
				, 'last_name' => 'Spelleiding'
				, 'gender' => 'F'
				, 'date_of_birth' => '1987-06-05'
				, 'modified' => '2018-01-01 01:00:00'
				, 'modifier_id' => 5
				]
			,	[ 'id' => 4
				, 'role' => 'Infobalie'
				, 'password' => $password
				, 'first_name' => 'In'
				, 'insertion' => 'fo'
				, 'last_name' => 'Balie'
				, 'gender' => 'M'
				, 'date_of_birth' => '1987-06-05'
				, 'modified' => NULL
				, 'modifier_id' => 1
				]
			,	[ 'id' => 5
				, 'role' => 'Super'
				, 'password' => $password
				, 'first_name' => 'Super'
				, 'insertion' => NULL
				, 'last_name' => 'User'
				, 'gender' => 'F'
				, 'date_of_birth' => '1987-06-05'
				, 'modified' => NULL
				, 'modifier_id' => NULL
				]
			,	[ 'id' => 6
				, 'role' => 'Player'
				, 'password' => NULL
				, 'first_name' => 'no'
				, 'insertion' => NULL
				, 'last_name' => 'login'
				, 'gender' => NULL
				, 'date_of_birth' => NULL
				, 'modified' => NULL
				, 'modifier_id' => NULL
				]
			];
		parent::init();
	}
}
