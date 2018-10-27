<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class TeachingsFixture
	extends TestFixture
{

	public $fields =
		[ 'student_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null]
		, 'teacher_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null]
		, 'skill_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null]
		, 'started_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null]
		, 'updated_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null]
		, 'xp' => ['type' => 'decimal', 'length' => 3, 'precision' => 1, 'unsigned' => false, 'null' => false, 'default' => '0.0', 'comment' => '']
		, 'modified' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null]
		, 'modifier_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null]
		, '_indexes' =>
			[ 'teachings_ibfk_2' => ['type' => 'index', 'columns' => ['teacher_id'], 'length' => []]
			, 'teachings_ibfk_3' => ['type' => 'index', 'columns' => ['skill_id'], 'length' => []]
			, 'teachings_ibfk_4' => ['type' => 'index', 'columns' => ['started_id'], 'length' => []]
			, 'teachings_ibfk_5' => ['type' => 'index', 'columns' => ['updated_id'], 'length' => []]
			]
		, '_constraints' =>
			[ 'primary' => ['type' => 'primary', 'columns' => ['student_id'], 'length' => []]
			, 'teachings_ibfk_1' => ['type' => 'foreign', 'columns' => ['student_id'], 'references' => ['characters', 'id'], 'update' => 'cascade', 'delete' => 'restrict', 'length' => []]
			, 'teachings_ibfk_2' => ['type' => 'foreign', 'columns' => ['teacher_id'], 'references' => ['characters', 'id'], 'update' => 'cascade', 'delete' => 'restrict', 'length' => []]
			, 'teachings_ibfk_3' => ['type' => 'foreign', 'columns' => ['skill_id'], 'references' => ['skills', 'id'], 'update' => 'cascade', 'delete' => 'restrict', 'length' => []]
			, 'teachings_ibfk_4' => ['type' => 'foreign', 'columns' => ['started_id'], 'references' => ['events', 'id'], 'update' => 'cascade', 'delete' => 'restrict', 'length' => []]
			, 'teachings_ibfk_5' => ['type' => 'foreign', 'columns' => ['updated_id'], 'references' => ['events', 'id'], 'update' => 'cascade', 'delete' => 'restrict', 'length' => []]
			]
		, '_options' =>
			[ 'engine' => 'InnoDB'
			, 'collation' => 'utf8_general_ci'
			]
		];

	public function init()
	{
		$this->records =
			[	[ 'student_id' => 1
				, 'teacher_id' => 2
				, 'skill_id' => 1
				, 'started_id' => 1
				, 'updated_id' => 1
				, 'xp' => 0
				, 'modified' => NULL
				, 'modifier_id' => NULL
				]
			,	[ 'student_id' => 2
				, 'teacher_id' => 1
				, 'skill_id' => 1
				, 'started_id' => 1
				, 'updated_id' => 1
				, 'xp' => 0
				, 'modified' => NULL
				, 'modifier_id' => NULL
				]
			];
		parent::init();
	}
}
