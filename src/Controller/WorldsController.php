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
			[ 'contain' => [ 'Characters' ] ]);

		$this->Crud->action('add')->config(
			[ 'relatedModels' => [ 'Characters' ] ]);

		$this->Crud->action('edit')->config(
			[ 'contain' => [ 'Characters' ]
			, 'relatedModels' => [ 'Characters' ]
			]);
	}

}
