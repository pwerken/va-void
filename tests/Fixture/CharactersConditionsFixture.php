<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * CharactersConditionsFixture
 *
 */
class CharactersConditionsFixture extends TestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = [
		'character_id' => ['type' => 'integer', 'length' => 10, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
		'condition_id' => ['type' => 'integer', 'length' => 10, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
		'expiry' => ['type' => 'date', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
		'_indexes' => [
			'characters_conditions_condition_key' => ['type' => 'index', 'columns' => ['condition_id'], 'length' => []],
		],
		'_constraints' => [
			'primary' => ['type' => 'primary', 'columns' => ['character_id', 'condition_id'], 'length' => []],
			'characters_conditions_character_key' => ['type' => 'foreign', 'columns' => ['character_id'], 'references' => ['characters', 'id'], 'update' => 'cascade', 'delete' => 'restrict', 'length' => []],
			'characters_conditions_condition_key' => ['type' => 'foreign', 'columns' => ['condition_id'], 'references' => ['conditions', 'id'], 'update' => 'cascade', 'delete' => 'restrict', 'length' => []],
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
			'character_id' => 1,
			'condition_id' => 1,
			'expiry' => '2014-11-17'
		],
	];

}
