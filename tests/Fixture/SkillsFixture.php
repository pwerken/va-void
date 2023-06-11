<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class SkillsFixture
	extends TestFixture
{
	public function init(): void
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
