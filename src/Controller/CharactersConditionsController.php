<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * CharactersConditions Controller
 *
 * @property App\Model\Table\CharactersConditionsTable $CharactersConditions
 */
class CharactersConditionsController extends AppController {

	public function initialize() {
		parent::initialize();

		$this->Crud->action('index')->config(
			[ 'contain' => [ 'Characters', 'Conditions' ] ]);

		$this->Crud->action('view')->config(
			[ 'contain' => [ 'Characters', 'Conditions' ] ]);

		$this->Crud->action('add')->config(
			[ 'relatedModels' => [ 'Characters', 'Conditions' ] ]);

		$this->Crud->action('edit')->config(
			[ 'relatedModels' => [ 'Characters', 'Conditions' ] ]);
	}

}
