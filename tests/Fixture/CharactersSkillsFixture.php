<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class CharactersSkillsFixture
	extends TestFixture
{

    public $fields =
		[ 'character_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null]
		, 'skill_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null]
		, 'modified' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null]
		, 'modifier_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null]
		, '_indexes' =>
			[ 'characters_skills_character_id' => ['type' => 'index', 'columns' => ['character_id'], 'length' => []]
			, 'characters_skills_skill_id' => ['type' => 'index', 'columns' => ['skill_id'], 'length' => []]
			]
        , '_constraints' =>
			[ 'primary' => ['type' => 'primary', 'columns' => ['character_id', 'skill_id'], 'length' => []]
			, 'characters_skills_ibfk_1' => ['type' => 'foreign', 'columns' => ['character_id'], 'references' => ['characters', 'id'], 'update' => 'cascade', 'delete' => 'restrict', 'length' => []]
			, 'characters_skills_ibfk_2' => ['type' => 'foreign', 'columns' => ['skill_id'], 'references' => ['skills', 'id'], 'update' => 'cascade', 'delete' => 'restrict', 'length' => []]
			]
		, '_options' =>
			[ 'engine' => 'InnoDB'
			, 'collation' => 'utf8_general_ci'
			]
		];

	public function init(): void
	{
		$this->records =
			[	[ 'character_id' => 1
				, 'skill_id' => 1
				, 'modified' => '2018-06-23 14:38:08'
				, 'modifier_id' => 1
				]
			];
		parent::init();
	}
}
