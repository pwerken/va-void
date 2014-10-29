<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

/**
 * AttributesItems Controller
 *
 * @property App\Model\Table\AttributesItemsTable $AttributesItems
 */
class AttributesItemsController extends AppController {

	public function index() {
		$this->Crud->on('beforePaginate', function(Event $event) {
			$this->paginate =
				[ 'contain' => [ 'Attributes', 'Items' ]
				];
		});
		return $this->Crud->execute();
	}

	public function view($id = null) {
		$this->Crud->on('beforeFind', function(Event $event) {
			$event->subject->query->contain([ 'Attributes', 'Items' ]);
		});
		return $this->Crud->execute();
	}

	public function add() {
		$this->Crud->listener('relatedModels')->relatedModels(
				[ 'Attributes', 'Items' ]);
		$this->Crud->execute();
	}

	public function edit($id = null) {
		$this->Crud->listener('relatedModels')->relatedModels(
				[ 'Attributes', 'Items' ]);
		$this->Crud->execute();
	}

}
