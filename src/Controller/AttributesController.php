<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Attributes Controller
 *
 * @property App\Model\Table\AttributesTable $Attributes
 */
class AttributesController extends AppController {

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
			return $this->hasAuthReferee();
		default:
			return parent::isAuthorized($user);
		}
	}
}
