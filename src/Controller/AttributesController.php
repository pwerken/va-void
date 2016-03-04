<?php
namespace App\Controller;

use App\Controller\AppController;

class AttributesController
	extends AppController
{

	public function initialize()
	{
		parent::initialize();

		$this->mapMethod('index', [ 'referee' ]);
		$this->mapMethod('view',  [ 'referee' ]);
	}

}
