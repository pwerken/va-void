<?php
namespace App\Controller;

use App\Controller\AppController;

class SkillsController
	extends AppController
{

	public function initialize()
	{
		parent::initialize();

		$contain = [ 'Manatypes' ];

		$this->mapMethod('index', [ 'player' ], $contain);
		$this->mapMethod('view',  [ 'player' ], $contain);
	}

}
