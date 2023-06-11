<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\Auth\DefaultPasswordHasher;
use Cake\TestSuite\Fixture\TestFixture;

class PlayersFixture
	extends TestFixture
{
<<<<<<< HEAD
	public $fields =
		[ 'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null]
		, 'role' => ['type' => 'string', 'length' => 255, 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null]
		, 'password' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null]
		, 'first_name' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null]
		, 'insertion' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null]
		, 'last_name' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null]
		, 'gender' => ['type' => 'string', 'length' => 1, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null]
		, 'date_of_birth' => ['type' => 'date', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null]
		, 'modified' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null]
		, 'modifier_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null]
		, '_indexes' =>
			[
			]
		, '_constraints' =>
			[ 'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []]
			]
		, '_options' =>
			[ 'engine' => 'InnoDB'
			, 'collation' => 'utf8_general_ci'
			]
		];

=======
>>>>>>> 0533d1f (tests: use migration for testdb setup instead of fixtures)
	public function init(): void
	{
		$hasher = new DefaultPasswordHasher();
		$password = $hasher->hash('password');

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
