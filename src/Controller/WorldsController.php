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
