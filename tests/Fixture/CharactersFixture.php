<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * CharactersFixture
 *
 */
class CharactersFixture extends TestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = [
		'id' => ['type' => 'integer', 'length' => 10, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
		'player_id' => ['type' => 'integer', 'length' => 10, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => 'PLIN', 'precision' => null, 'autoIncrement' => null],
		'chin' => ['type' => 'integer', 'length' => 2, 'unsigned' => true, 'null' => false, 'default' => '1', 'comment' => '', 'precision' => null, 'autoIncrement' => null],
		'name' => ['type' => 'string', 'length' => 255, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
		'xp' => ['type' => 'decimal', 'length' => 4, 'precision' => 1, 'unsigned' => true, 'null' => false, 'default' => '15.0', 'comment' => ''],
		'faction_id' => ['type' => 'integer', 'length' => 10, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
		'belief_id' => ['type' => 'integer', 'length' => 10, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
		'group_id' => ['type' => 'integer', 'length' => 10, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
		'world_id' => ['type' => 'integer', 'length' => 10, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
		'status' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
		'comments' => ['type' => 'text', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
		'created' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
		'modified' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
		'_indexes' => [
			'player_idx' => ['type' => 'index', 'columns' => ['player_id'], 'length' => []],
			'belief_idx' => ['type' => 'index', 'columns' => ['belief_id'], 'length' => []],
			'group_idx' => ['type' => 'index', 'columns' => ['group_id'], 'length' => []],
			'world_idx' => ['type' => 'index', 'columns' => ['world_id'], 'length' => []],
			'faction_idx' => ['type' => 'index', 'columns' => ['faction_id'], 'length' => []],
		],
		'_constraints' => [
			'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
			'characters_belief_key' => ['type' => 'foreign', 'columns' => ['belief_id'], 'references' => ['believes', 'id'], 'update' => 'cascade', 'delete' => 'restrict', 'length' => []],
			'characters_faction_key' => ['type' => 'foreign', 'columns' => ['faction_id'], 'references' => ['factions', 'id'], 'update' => 'cascade', 'delete' => 'restrict', 'length' => []],
			'characters_group_key' => ['type' => 'foreign', 'columns' => ['group_id'], 'references' => ['groups', 'id'], 'update' => 'cascade', 'delete' => 'restrict', 'length' => []],
			'characters_player_key' => ['type' => 'foreign', 'columns' => ['player_id'], 'references' => ['players', 'id'], 'update' => 'cascade', 'delete' => 'restrict', 'length' => []],
			'characters_world_key' => ['type' => 'foreign', 'columns' => ['world_id'], 'references' => ['worlds', 'id'], 'update' => 'cascade', 'delete' => 'restrict', 'length' => []],
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
			'id' => 1,
			'player_id' => 1,
			'chin' => 1,
			'name' => 'Lorem ipsum dolor sit amet',
			'xp' => '',
			'faction_id' => 1,
			'belief_id' => 1,
			'group_id' => 1,
			'world_id' => 1,
			'status' => 'Lorem ipsum dolor sit amet',
			'comments' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'created' => '2014-10-29 18:32:11',
			'modified' => '2014-10-29 18:32:11'
		],
	];

}
