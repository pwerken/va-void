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

		$this->Crud->action('view')->config(
			[ 'contain' =>
				[ 'Characters' =>
					[ 'Factions'
					, 'Believes'
					, 'Groups'
					, 'Worlds'
				]	]
			]);

		$this->Crud->action('add')->config(
			[ 'relatedModels' => [ 'Characters' ] ]);

		$this->Crud->action('edit')->config(
			[ 'contain' => [ 'Characters' ]
			, 'relatedModels' => [ 'Characters' ]
			]);
	}

}
