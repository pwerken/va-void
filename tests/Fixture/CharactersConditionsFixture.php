<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class CharactersConditionsFixture
	extends TestFixture
{
	public function init(): void
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
