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
		$this->Crud->mapAction('view',
			[ 'className' => 'Crud.View'
#			, 'contain' => [ 'Characters' ]
			]);
	}

}
