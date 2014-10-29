<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * CharactersSkillsFixture
 *
 */
class CharactersSkillsFixture extends TestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = [
		'character_id' => ['type' => 'integer', 'length' => 10, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
		'skill_id' => ['type' => 'integer', 'length' => 10, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
		'_indexes' => [
			'characters_skills_skill_key' => ['type' => 'index', 'columns' => ['skill_id'], 'length' => []],
		],
		'_constraints' => [
			'primary' => ['type' => 'primary', 'columns' => ['character_id', 'skill_id'], 'length' => []],
			'characters_skills_character_key' => ['type' => 'foreign', 'columns' => ['character_id'], 'references' => ['characters', 'id'], 'update' => 'cascade', 'delete' => 'restrict', 'length' => []],
			'characters_skills_skill_key' => ['type' => 'foreign', 'columns' => ['skill_id'], 'references' => ['skills', 'id'], 'update' => 'cascade', 'delete' => 'restrict', 'length' => []],
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
			'skill_id' => 1
		],
	];

}
