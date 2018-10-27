<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class BelievesFixture
	extends TestFixture
{

	public $import = ['table' => 'believes', 'connection' => 'default'];

	public function init()
	{
		$this->records =
			[	[ 'id' => 1
				, 'name' => '-'
				, 'modified' => NULL
				, 'modifier_id' => NULL
				]
			,	[ 'id' => 2
				, 'name' => 'Self'
				, 'modified' => NULL
				, 'modifier_id' => NULL
				]
			];
		parent::init();
	}
}
