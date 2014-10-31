<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Items Controller
 *
 * @property App\Model\Table\ItemsTable $Items
 */
class ItemsController extends AppController {

	public function initialize() {
		parent::initialize();

		$this->Crud->action('index')->config(
			[ 'contain' => [ 'Characters' ] ]);

		$this->Crud->action('view')->config(
			[ 'contain' => [ 'Characters', 'Attributes' ] ]);

		$this->Crud->action('add')->config(
			[ 'relatedModels' => [ 'Characters', 'Attributes' ] ]);

		$this->Crud->action('edit')->config(
			[ 'contain' => [ 'Attributes' ]
			, 'relatedModels' => [ 'Characters', 'Attributes' ]
			]);
	}

}
