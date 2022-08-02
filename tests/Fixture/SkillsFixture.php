<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class SkillsFixture
	extends TestFixture
{
	public $fields =
		[ 'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null]
		, 'name' => ['type' => 'string', 'length' => 255, 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null]
		, 'cost' => ['type' => 'integer', 'length' => 10, 'unsigned' => false, 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null]
		, 'manatype_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null]
		, 'mana_amount' => ['type' => 'integer', 'length' => 10, 'unsigned' => false, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null]
		, 'sort_order' => ['type' => 'integer', 'length' => 10, 'unsigned' => false, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null]
		, 'deprecated' => ['type' => 'boolean', 'length' => 1, 'unsigned' => false, 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null]
		, '_indexes' =>
			[ 'manatype_id' => ['type' => 'index', 'columns' => ['manatype_id'], 'length' => []]
			, 'sort_order' => ['type' => 'index', 'columns' => ['sort_order', 'name'], 'length' => []]
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
				, 'name' => 'Casting'
				, 'cost' => 5
				, 'manatype_id' => 1
				, 'mana_amount' => 10
				, 'sort_order' => 1
				, 'deprecated' => False
				]
			];
		parent::init();
	}
}
