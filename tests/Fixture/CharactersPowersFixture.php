<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * CharactersPowersFixture
 *
 */
class CharactersPowersFixture extends TestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = [
		'character_id' => ['type' => 'integer', 'length' => 10, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
		'power_id' => ['type' => 'integer', 'length' => 10, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
		'expiry' => ['type' => 'date', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
		'_indexes' => [
			'characters_powers_power_key' => ['type' => 'index', 'columns' => ['power_id'], 'length' => []],
		],
		'_constraints' => [
			'primary' => ['type' => 'primary', 'columns' => ['character_id', 'power_id'], 'length' => []],
			'characters_powers_character_key' => ['type' => 'foreign', 'columns' => ['character_id'], 'references' => ['characters', 'id'], 'update' => 'cascade', 'delete' => 'restrict', 'length' => []],
			'characters_powers_power_key' => ['type' => 'foreign', 'columns' => ['power_id'], 'references' => ['powers', 'id'], 'update' => 'cascade', 'delete' => 'restrict', 'length' => []],
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
			'power_id' => 1,
			'expiry' => '2014-11-17'
		],
	];

}
