<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * CharactersSpells Controller
 *
 * @property App\Model\Table\CharactersSpellsTable $CharactersSpells
 */
class CharactersSpellsController extends AppController {

	public function initialize() {
		parent::initialize();

		$this->Crud->action('index')->config(
			[ 'contain' => [ 'Characters', 'Spells' ] ]);

		$this->Crud->action('view')->config(
			[ 'contain' => [ 'Characters', 'Spells' ] ]);

		$this->Crud->action('add')->config(
			[ 'relatedModels' => [ 'Characters', 'Spells' ] ]);

		$this->Crud->action('edit')->config(
			[ 'relatedModels' => [ 'Characters', 'Spells' ] ]);
	}

}
