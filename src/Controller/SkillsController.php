<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Skills Controller
 *
 * @property App\Model\Table\SkillsTable $Skills
 */
class SkillsController extends AppController {

	public function initialize() {
		parent::initialize();

		$this->Crud->action('index')->config(
			[ 'contain' => [ 'Manatypes' ] ]);

		$this->Crud->action('view')->config(
			[ 'contain' => [ 'Manatypes' ] ]);

		$this->Crud->action('add')->config(
			[ 'relatedModels' => [ 'Manatypes' ] ]);

		$this->Crud->action('edit')->config(
			[ 'contain' => [ 'Characters' ]
			, 'relatedModels' => [ 'Characters', 'Manatypes' ]
			]);
	}

}
