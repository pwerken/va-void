<?php
namespace App\Controller;

use App\Controller\AppController;

class GroupsController
	extends AppController
{

	public function initialize()
	{
		parent::initialize();

		$this->mapMethod('index', [ 'player' ]);
		$this->mapMethod('view',  [ 'player' ]);
	}

}
