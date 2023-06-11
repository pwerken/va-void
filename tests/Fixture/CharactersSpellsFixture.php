<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class CharactersSpellsFixture
	extends TestFixture
{
	public function init(): void
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
