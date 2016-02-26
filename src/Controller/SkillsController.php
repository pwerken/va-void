<?php
namespace App\Controller;

use App\Controller\AppController;

class SkillsController
	extends AppController
{

	public function initialize()
	{
		parent::initialize();

		$this->Crud->mapAction('index',
			[ 'className' => 'Crud.Index'
			, 'contain' => [ 'Manatypes' ]
			]);
		$this->Crud->mapAction('view',
			[ 'className' => 'Crud.View'
			, 'contain' => [ 'Manatypes' ]
			]);
	}

	public function isAuthorized($user)
	{
		switch($this->request->action) {
		case 'index':
		case 'view':
			return $this->hasAuthPlayer();
		default:
			return parent::isAuthorized($user);
		}
	}
}
