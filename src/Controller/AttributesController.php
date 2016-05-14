<?php
namespace App\Controller;

class AttributesController
	extends AppController
{

	protected $searchFields =
		[ 'Attributes.name'
		, 'Attributes.code'
		];

	public function initialize()
	{
		parent::initialize();

		$this->mapMethod('add',    [ 'super'   ]);
		$this->mapMethod('delete', [ 'super'   ]);
		$this->mapMethod('edit',   [ 'super'   ]);
		$this->mapMethod('index',  [ 'referee' ]);
		$this->mapMethod('view',   [ 'referee' ]);
	}

}
