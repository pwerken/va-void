<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class SkillsFixture
	extends TestFixture
{

	public $import = ['table' => 'skills', 'connection' => 'default'];

	public function init()
	{
		$this->records =
			[	[ 'id' => 1
				, 'name' => 'Casting'
				, 'cost' => 5
				, 'manatype_id' => 1
				, 'mana_amount' => 10
				, 'sort_order' => 1
				, 'deprecated' => False
				]
			];
		parent::init();
	}
}
