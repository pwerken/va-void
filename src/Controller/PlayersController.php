<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Players Controller
 *
 * @property App\Model\Table\PlayersTable $Players
 */
class PlayersController extends AppController {

	public function initialize() {
		parent::initialize();

		$this->Crud->mapAction('index', 'Crud.Index');
		$this->Crud->mapAction('view',
			[ 'className' => 'Crud.View'
			, 'contain' => [ 'Characters' ]
			]);
	}

}
