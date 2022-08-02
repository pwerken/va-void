<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class CharactersFixture
	extends TestFixture
{
	public $fields =
		[ 'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null]
		, 'player_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null]
		, 'chin' => ['type' => 'integer', 'length' => 2, 'unsigned' => true, 'null' => false, 'default' => 1, 'comment' => '', 'autoIncrement' => true, 'precision' => null]
		, 'name' => ['type' => 'string', 'length' => 255, 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null]
		, 'xp' => ['type' => 'integer', 'length' => 5, 'unsigned' => true, 'null' => false, 'default' => 15.00, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => 2, 'fixed' => null]
		, 'faction_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null]
		, 'belief_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null]
		, 'group_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null]
		, 'world_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null]
		, 'soulpath' => ['type' => 'string', 'length' => 2, 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null]
		, 'status' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null]
		, 'referee_notes' => ['type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null]
		, 'notes' => ['type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null]
		, 'modified' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null]
		, 'modifier_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null]
		, '_indexes' =>
			[ 'player_id' => ['type' => 'index', 'columns' => ['player_id'], 'length' => []]
			, 'player_id_2' => ['type' => 'index', 'columns' => ['player_id', 'chin'], 'length' => []]
			, 'belief_id' => ['type' => 'index', 'columns' => ['belief_id'], 'length' => []]
			, 'faction_id' => ['type' => 'index', 'columns' => ['faction_id'], 'length' => []]
			, 'group_id' => ['type' => 'index', 'columns' => ['group_id'], 'length' => []]
			, 'worlds_id' => ['type' => 'index', 'columns' => ['world_id'], 'length' => []]
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
				, 'player_id' => 1
				, 'chin' => 1
				, 'name' => 'Sir Killalot'
				, 'xp' => 15.0
				, 'faction_id' => 1
				, 'belief_id' => 1
				, 'group_id' => 1
				, 'world_id' => 1
				, 'soulpath' => ''
				, 'status' => 'Active'
				, 'comments' => ''
				, 'modified' => NULL
				, 'modifier_id' => NULL
				]
			,	[ 'id' => 2
				, 'player_id' => 2
				, 'chin' => 1
				, 'name' => 'Mathilda'
				, 'xp' => 15.0
				, 'faction_id' => 2
				, 'belief_id' => 2
				, 'group_id' => 2
				, 'world_id' => 2
				, 'soulpath' => 'MO'
				, 'status' => 'Active'
				, 'comments' => ''
				, 'modified' => NULL
				, 'modifier_id' => NULL
				]
			];
		parent::init();
	}
}
