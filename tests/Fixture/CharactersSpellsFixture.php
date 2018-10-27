<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class CharactersSpellsFixture
	extends TestFixture
{

    public $fields =
		[ 'character_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null]
		, 'spell_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null]
		, 'level' => ['type' => 'integer', 'length' => 10, 'unsigned' => false, 'null' => false, 'default' => '1', 'comment' => '', 'precision' => null, 'autoIncrement' => null]
		, 'modified' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null]
		, 'modifier_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null]
		, '_indexes' =>
            [ 'characters_spells_character_id' => ['type' => 'index', 'columns' => ['character_id'], 'length' => []]
			, 'characters_spells_spell_id' => ['type' => 'index', 'columns' => ['spell_id'], 'length' => []]
			]
		, '_constraints' =>
			[ 'primary' => ['type' => 'primary', 'columns' => ['character_id', 'spell_id'], 'length' => []]
			, 'characters_spells_ibfk_1' => ['type' => 'foreign', 'columns' => ['character_id'], 'references' => ['characters', 'id'], 'update' => 'cascade', 'delete' => 'restrict', 'length' => []]
			, 'characters_spells_ibfk_2' => ['type' => 'foreign', 'columns' => ['spell_id'], 'references' => ['spells', 'id'], 'update' => 'cascade', 'delete' => 'restrict', 'length' => []]
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
				, 'spell_id' => 1
				, 'level' => 1
				, 'modified' => '2018-06-23 14:38:08'
				, 'modifier_id' => 1
				]
			];
		parent::init();
	}
}
