<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Conditions Controller
 *
 * @property App\Model\Table\ConditionsTable $Conditions
 */
class ConditionsController extends AppController {

	public function initialize() {
		parent::initialize();

		$this->Crud->mapAction('index', 'Crud.Index');
		$this->Crud->mapAction('view',
			[ 'className' => 'Crud.View'
			, 'contain' => [ 'Characters' ]
			]);
	}

}
