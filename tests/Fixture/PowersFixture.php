<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class PowersFixture
	extends TestFixture
{
	public function init(): void
	{
		$this->records =
			[	[ 'id' => 1
				, 'name' => 'Magic'
				, 'player_text' => 'You can do magics'
				, 'cs_text' => ''
				, 'modified' => NULL
				, 'modifier_id' => NULL
				]
			,	[ 'id' => 2
				, 'name' => 'Yes'
				, 'player_text' => 'You you can.'
				, 'cs_text' => ''
				, 'modified' => NULL
				, 'modifier_id' => NULL
				]
			];
		parent::init();
	}
}
