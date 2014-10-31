<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * AttributesItems Controller
 *
 * @property App\Model\Table\AttributesItemsTable $AttributesItems
 */
class AttributesItemsController extends AppController {

	public function initialize() {
		parent::initialize();

		$this->Crud->action('index')->config(
			[ 'contain' => [ 'Attributes', 'Items' ] ]);

		$this->Crud->action('view')->config(
			[ 'contain' => [ 'Attributes', 'Items' ] ]);

		$this->Crud->action('add')->config(
			[ 'relatedModels' => [ 'Attributes', 'Items' ] ]);

		$this->Crud->action('edit')->config(
			[ 'relatedModels' => [ 'Attributes', 'Items' ] ]);
	}

}
