<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class AttributesItemsFixture
	extends TestFixture
{

    public $fields =
		[ 'attribute_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null]
		, 'item_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null]
		, 'modified' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null]
		, 'modifier_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null]
		, '_indexes' =>
			[ 'atttribute_id' => ['type' => 'index', 'columns' => ['attribute_id'], 'length' => []]
			, 'item_id' => ['type' => 'index', 'columns' => ['item_id'], 'length' => []]
			]
		, '_constraints' =>
			[ 'primary' => ['type' => 'primary', 'columns' => ['attribute_id', 'item_id'], 'length' => []]
			, 'attributes_items_ibfk_1' => ['type' => 'foreign', 'columns' => ['attribute_id'], 'references' => ['attributes', 'id'], 'update' => 'cascade', 'delete' => 'restrict', 'length' => []]
			, 'attributes_items_ibfk_2' => ['type' => 'foreign', 'columns' => ['item_id'], 'references' => ['items', 'id'], 'update' => 'cascade', 'delete' => 'restrict', 'length' => []]
			]
		, '_options' =>
			[ 'engine' => 'InnoDB'
			, 'collation' => 'utf8_general_ci'
			]
		];

	public function init(): void
	{
		$this->records =
			[	[ 'attribute_id' => 1
				, 'item_id' => 1
				, 'modified' => '2018-06-23 14:30:13'
				, 'modifier_id' => 1
				]
			,	[ 'attribute_id' => 2
				, 'item_id' => 2
				, 'modified' => '2018-06-23 14:30:13'
				, 'modifier_id' => 1
				]
			];
		parent::init();
	}
}
