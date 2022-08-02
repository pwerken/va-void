<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class PowersFixture
	extends TestFixture
{

	public $fields =
		[ 'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null]
		, 'name' => ['type' => 'string', 'length' => 255, 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null]
		, 'player_text' => ['type' => 'text', 'length' => null, 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null]
		, 'cs_text' => ['type' => 'text', 'length' => null, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null]
		, 'modified' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null]
		, 'modifier_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null]
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
				, 'name' => 'Magic'
				, 'player_text' => 'You can do magics'
				, 'cs_text' => ''
				, 'modified' => NULL
				, 'modifier_id' => NULL
				]
			,	[ 'id' => 2
				, 'name' => 'Yes'
				, 'player_text' => 'You you can.'
				, 'cs_text' => ''
				, 'modified' => NULL
				, 'modifier_id' => NULL
				]
			];
		parent::init();
	}
}
