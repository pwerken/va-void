<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Attributes Controller
 *
 * @property App\Model\Table\AttributesTable $Attributes
 */
class AttributesController extends AppController {

	public function initialize() {
		parent::initialize();

		$this->Crud->mapAction('index', 'Crud.Index');
		$this->Crud->mapAction('view',
			[ 'className' => 'Crud.View'
			, 'contain' => [ 'Items' => [ 'Characters' ] ]
			]);

	}
}
