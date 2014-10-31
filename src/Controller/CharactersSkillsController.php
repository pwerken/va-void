<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * CharactersSkills Controller
 *
 * @property App\Model\Table\CharactersSkillsTable $CharactersSkills
 */
class CharactersSkillsController extends AppController {

	public function initialize() {
		parent::initialize();

		$this->Crud->action('index')->config(
			[ 'contain' => [ 'Characters', 'Skills' ] ]);

		$this->Crud->action('view')->config(
			[ 'contain' => [ 'Characters', 'Skills' ] ]);

		$this->Crud->action('add')->config(
			[ 'relatedModels' => [ 'Characters', 'Skills' ] ]);

		$this->Crud->action('edit')->config(
			[ 'relatedModels' => [ 'Characters', 'Skills' ] ]);
	}

}
