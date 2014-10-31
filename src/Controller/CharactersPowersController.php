<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * CharactersPowers Controller
 *
 * @property App\Model\Table\CharactersPowersTable $CharactersPowers
 */
class CharactersPowersController extends AppController {

	public function initialize() {
		parent::initialize();

		$this->Crud->action('index')->config(
			[ 'contain' => [ 'Characters', 'Powers' ] ]);

		$this->Crud->action('view')->config(
			[ 'contain' => [ 'Characters', 'Powers' ] ]);

		$this->Crud->action('add')->config(
			[ 'relatedModels' => [ 'Characters', 'Powers' ] ]);

		$this->Crud->action('edit')->config(
			[ 'relatedModels' => [ 'Characters', 'Powers' ] ]);
	}

}
