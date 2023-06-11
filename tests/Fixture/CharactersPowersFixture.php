<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class CharactersPowersFixture
	extends TestFixture
{
	public function init(): void
	{
		$this->records =
			[	[ 'character_id' => 1
				, 'power_id' => 1
				, 'expiry' => NULL
				, 'modified' => NULL
				, 'modifier_id' => NULL
				]
			,	[ 'character_id' => 2
				, 'power_id' => 2
				, 'expiry' => NULL
				, 'modified' => NULL
				, 'modifier_id' => NULL
				]
			];
		parent::init();
	}
}
