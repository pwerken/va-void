<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class CharactersFixture
	extends TestFixture
{

	public $import = ['table' => 'characters', 'connection' => 'default'];

	public function init()
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
