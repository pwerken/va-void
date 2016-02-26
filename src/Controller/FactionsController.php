<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Factions Controller
 *
 * @property App\Model\Table\FactionsTable $Factions
 */
class FactionsController extends AppController {

	public function initialize() {
		parent::initialize();

		$this->Crud->mapAction('index', 'Crud.Index');
		$this->Crud->mapAction('view',  'Crud.View');
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
