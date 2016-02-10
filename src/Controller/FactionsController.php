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
		$this->Crud->mapAction('view',
			[ 'className' => 'Crud.View'
			, 'contain' => [ 'Characters' ]
			]);
	}

}
