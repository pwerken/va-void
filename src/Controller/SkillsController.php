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

		$this->Crud->mapAction('index',
			[ 'className' => 'Crud.Index'
			, 'contain' => [ 'Manatypes' ]
			]);
		$this->Crud->mapAction('view',
			[ 'className' => 'Crud.View'
			, 'contain' => [ 'Manatypes' ]
			]);
	}

}
