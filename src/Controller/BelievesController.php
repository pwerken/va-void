<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Believes Controller
 *
 * @property App\Model\Table\BelievesTable $Believes
 */
class BelievesController extends AppController {

	public function initialize() {
		parent::initialize();

		$this->Crud->mapAction('index', 'Crud.Index');
		$this->Crud->mapAction('view',
			[ 'className' => 'Crud.View'
			, 'contain' => [ 'Characters' ]
			]);
	}

}
