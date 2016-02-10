<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Worlds Controller
 *
 * @property App\Model\Table\WorldsTable $Worlds
 */
class WorldsController extends AppController {

	public function initialize() {
		parent::initialize();

		$this->Crud->mapAction('index', 'Crud.Index');
		$this->Crud->mapAction('view',
			[ 'className' => 'Crud.View'
#			, 'contain' => [ 'Characters' ]
			]);
	}

}
