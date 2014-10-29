<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

/**
 * Items Controller
 *
 * @property App\Model\Table\ItemsTable $Items
 */
class ItemsController extends AppController {

	public function index() {
		$this->Crud->on('beforePaginate', function(Event $event) {
			$this->paginate =
				[ 'contain' => [ 'Characters' ]
				];
		});
		return $this->Crud->execute();
	}

	public function view($id = null) {
		$this->Crud->on('beforeFind', function(Event $event) {
			$event->subject->query->contain([ 'Characters', 'Attributes' ]);
		});
		return $this->Crud->execute();
	}

	public function add() {
		$this->Crud->listener('relatedModels')->relatedModels(
				[ 'Characters', 'Attributes' ]);
		$this->Crud->execute();
	}

	public function edit($id = null) {
		$this->Crud->on('beforeFind', function(Event $event) {
			$event->subject->query->contain([ 'Attributes' ]);
		});
		$this->Crud->listener('relatedModels')->relatedModels(
				[ 'Characters', 'Attributes' ]);
		$this->Crud->execute();
	}

}
