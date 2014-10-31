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

		$this->Crud->action('view')->config(
			[ 'contain' => [ 'Items' => [ 'Characters' ] ] ]);

		$this->Crud->action('add')->config(
			[ 'relatedModels' => [ 'Items' ] ]);

		$this->Crud->action('edit')->config(
			[ 'contain' => [ 'Items' ]
			, 'relatedModels' => [ 'Items' ]
			]);
	}

}
