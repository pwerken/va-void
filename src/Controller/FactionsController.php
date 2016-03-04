<?php
namespace App\Controller;

use App\Controller\AppController;

class FactionsController
	extends AppController
{

	public function initialize()
	{
		parent::initialize();

		$this->mapMethod('index', [ 'player' ]);
		$this->mapMethod('view',  [ 'player' ]);
	}

}
