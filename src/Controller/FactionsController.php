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

		$this->Crud->action('view')->config(
			[ 'contain' =>
				[ 'Characters' =>
					[ 'Factions'
					, 'Believes'
					, 'Groups'
					, 'Worlds'
				]	]
			]);
	}

}
