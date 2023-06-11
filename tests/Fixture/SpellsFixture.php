<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class SpellsFixture
	extends TestFixture
{
	public function init(): void
	{
		$this->records =
			[	[ 'id' => 1
				, 'name' => 'Spells'
				, 'short' => 'SP'
				, 'spiritual' => false
				]
			];
		parent::init();
	}
}
