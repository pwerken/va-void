<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class LammiesFixture
	extends TestFixture
{

	public $fields =
		[ 'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null]
		, 'status' => ['type' => 'string', 'length' => 255, 'null' => false, 'default' => 'Queued', 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null]
		, 'entity' => ['type' => 'string', 'length' => 255, 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null]
		, 'key1' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null]
		, 'key2' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null]
		, 'created' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null]
		, 'creator_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null]
		, 'modified' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null]
		, '_indexes' =>
			[ 'status' => ['type' => 'index', 'columns' => ['status', 'id'], 'length' => []]
			]
		, '_constraints' =>
			[ 'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []]
			]
		, '_options' =>
			[ 'engine' => 'InnoDB'
			, 'collation' => 'utf8_general_ci'
			]
		];

	public function init(): void
	{
		$this->records =
			[	[ 'id' => 1
				, 'status' => 'Queued'
				, 'entity' => 'Character'
				, 'key1' => 1
				, 'key2' => 1
				, 'created' => '2018-10-28 12:17:05'
				, 'creator_id' => 1
				, 'modified' => '2018-10-28 12:17:05'
				]
			,	[ 'id' => 2
				, 'status' => 'Queued'
				, 'entity' => 'CharactersCondition'
				, 'key1' => 1
				, 'key2' => 1
				, 'created' => '2018-10-28 12:17:05'
				, 'creator_id' => 1
				, 'modified' => '2018-10-28 12:17:05'
				]
			,	[ 'id' => 3
				, 'status' => 'Queued'
				, 'entity' => 'CharactersPower'
				, 'key1' => 1
				, 'key2' => 1
				, 'created' => '2018-10-28 12:17:05'
				, 'creator_id' => 1
				, 'modified' => '2018-10-28 12:17:05'
				]
			,	[ 'id' => 4
				, 'status' => 'Queued'
				, 'entity' => 'Condition'
				, 'key1' => 1
				, 'key2' => NULL
				, 'created' => '2018-10-28 12:17:05'
				, 'creator_id' => 1
				, 'modified' => '2018-10-28 12:17:05'
				]
			,	[ 'id' => 5
				, 'status' => 'Queued'
				, 'entity' => 'Power'
				, 'key1' => 1
				, 'key2' => NULL
				, 'created' => '2018-10-28 12:17:05'
				, 'creator_id' => 1
				, 'modified' => '2018-10-28 12:17:05'
				]
			,	[ 'id' => 6
				, 'status' => 'Queued'
				, 'entity' => 'Item'
				, 'key1' => 1
				, 'key2' => NULL
				, 'created' => '2018-10-28 12:17:05'
				, 'creator_id' => 1
				, 'modified' => '2018-10-28 12:17:05'
				]
			,	[ 'id' => 7
				, 'status' => 'Queued'
				, 'entity' => 'Teaching'
				, 'key1' => 1
				, 'key2' => 1
				, 'created' => '2018-10-28 12:17:05'
				, 'creator_id' => 1
				, 'modified' => '2018-10-28 12:17:05'
				]
			];
		parent::init();
	}
}
