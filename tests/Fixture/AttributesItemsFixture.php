<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class AttributesItemsFixture
	extends TestFixture
{

	public $import = ['table' => 'attributes_items', 'connection' => 'default'];

	public function init()
	{
		$this->records =
			[	[ 'attribute_id' => 1
				, 'item_id' => 1
				, 'modified' => '2018-06-23 14:30:13'
				, 'modifier_id' => 1
				]
			,	[ 'attribute_id' => 2
				, 'item_id' => 2
				, 'modified' => '2018-06-23 14:30:13'
				, 'modifier_id' => 1
				]
			];
		parent::init();
	}
}
