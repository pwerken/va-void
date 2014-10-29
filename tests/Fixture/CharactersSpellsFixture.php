<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * CharactersSpellsFixture
 *
 */
class CharactersSpellsFixture extends TestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = [
		'character_id' => ['type' => 'integer', 'length' => 10, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
		'spell_id' => ['type' => 'integer', 'length' => 10, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
		'level' => ['type' => 'integer', 'length' => 10, 'unsigned' => false, 'null' => false, 'default' => '1', 'comment' => '', 'precision' => null, 'autoIncrement' => null],
		'_indexes' => [
			'characters_spells_spell_key' => ['type' => 'index', 'columns' => ['spell_id'], 'length' => []],
		],
		'_constraints' => [
			'primary' => ['type' => 'primary', 'columns' => ['character_id', 'spell_id'], 'length' => []],
			'characters_spells_character_key' => ['type' => 'foreign', 'columns' => ['character_id'], 'references' => ['characters', 'id'], 'update' => 'cascade', 'delete' => 'cascade', 'length' => []],
			'characters_spells_spell_key' => ['type' => 'foreign', 'columns' => ['spell_id'], 'references' => ['spells', 'id'], 'update' => 'cascade', 'delete' => 'restrict', 'length' => []],
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
			'spell_id' => 1,
			'level' => 1
		],
	];

}
