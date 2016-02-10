<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Manatypes Controller
 *
 * @property App\Model\Table\ManatypesTable $Manatypes
 */
class ManatypesController extends AppController {

	public function initialize() {
		parent::initialize();

		$this->Crud->mapAction('index', 'Crud.Index');
		$this->Crud->mapAction('view',
			[ 'className' => 'Crud.View'
			, 'contain' => [ 'Skills' ]
			]);
	}

}
