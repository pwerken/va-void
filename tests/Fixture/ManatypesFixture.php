<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class ManatypesFixture
	extends TestFixture
{

	public $import = ['table' => 'manatypes', 'connection' => 'default'];

	public function init()
	{
		$this->records =
			[	[ 'id' => 1
				, 'name' => 'Mana'
				]
			];
		parent::init();
	}
}
