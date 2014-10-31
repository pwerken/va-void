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

		$this->Crud->action('add')->config(
			[ 'relatedModels' => [ 'Characters' ] ]);

		$this->Crud->action('edit')->config(
			[ 'contain' => [ 'Characters' ]
			, 'relatedModels' => [ 'Characters' ]
			]);
	}

}
