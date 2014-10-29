<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * AttributesItemsFixture
 *
 */
class AttributesItemsFixture extends TestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = [
		'attribute_id' => ['type' => 'integer', 'length' => 10, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
		'item_id' => ['type' => 'integer', 'length' => 10, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
		'_indexes' => [
			'attributes_items_item_key' => ['type' => 'index', 'columns' => ['item_id'], 'length' => []],
		],
		'_constraints' => [
			'primary' => ['type' => 'primary', 'columns' => ['attribute_id', 'item_id'], 'length' => []],
			'attributes_items_attribute_key' => ['type' => 'foreign', 'columns' => ['attribute_id'], 'references' => ['attributes', 'id'], 'update' => 'cascade', 'delete' => 'restrict', 'length' => []],
			'attributes_items_item_key' => ['type' => 'foreign', 'columns' => ['item_id'], 'references' => ['items', 'id'], 'update' => 'cascade', 'delete' => 'restrict', 'length' => []],
		],
		'_options' => [
'engine' => 'InnoDB', 'collation' => 'utf8_unicode_ci'
		],
	];

/**
 * Records
 *
 * @var array
 */
	public $records = [
		[
			'attribute_id' => 1,
			'item_id' => 1
		],
	];

}
