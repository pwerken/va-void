<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class CharactersConditionsFixture
	extends TestFixture
{

    public $fields =
		[ 'character_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null]
		, 'condition_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null]
		, 'expiry' => ['type' => 'date', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null]
		, 'modified' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null]
		, 'modifier_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null]
		, '_indexes' =>
			[ 'characters_conditions_character_id' => ['type' => 'index', 'columns' => ['character_id'], 'length' => []]
			, 'characters_conditions_condition_id' => ['type' => 'index', 'columns' => ['condition_id'], 'length' => []]
			]
		, '_constraints' =>
			[ 'primary' => ['type' => 'primary', 'columns' => ['character_id', 'condition_id'], 'length' => []]
			, 'characters_conditions_ibfk_1' => ['type' => 'foreign', 'columns' => ['character_id'], 'references' => ['characters', 'id'], 'update' => 'cascade', 'delete' => 'restrict', 'length' => []]
			, 'characters_conditions_ibfk_2' => ['type' => 'foreign', 'columns' => ['condition_id'], 'references' => ['conditions', 'id'], 'update' => 'cascade', 'delete' => 'restrict', 'length' => []]
			]
		, '_options' =>
			[ 'engine' => 'InnoDB'
			, 'collation' => 'utf8_general_ci'
			]
		];

	public function init()
	{
		$this->records =
			[	[ 'character_id' => 1
				, 'condition_id' => 1
				, 'expiry' => NULL
				, 'modified' => NULL
				, 'modifier_id' => NULL
				]
			,	[ 'character_id' => 2
				, 'condition_id' => 2
				, 'expiry' => NULL
				, 'modified' => NULL
				, 'modifier_id' => NULL
				]
			];
		parent::init();
	}
}
