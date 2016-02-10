<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Powers Controller
 *
 * @property App\Model\Table\PowersTable $Powers
 */
class PowersController extends AppController {

	public function initialize() {
		parent::initialize();

		$this->Crud->mapAction('index', 'Crud.Index');
		$this->Crud->mapAction('view',
			[ 'className' => 'Crud.View'
			, 'contain' => [ 'Characters' ]
			]);
	}

}
