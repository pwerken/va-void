<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Spells Controller
 *
 * @property App\Model\Table\SpellsTable $Spells
 */
class SpellsController extends AppController {

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
