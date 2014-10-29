<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

/**
 * Attributes Controller
 *
 * @property App\Model\Table\AttributesTable $Attributes
 */
class AttributesController extends AppController {

	public function view($id = null) {
		$this->Crud->on('beforeFind', function(Event $event) {
			$event->subject->query->contain([ 'Items' ]);
		});
		return $this->Crud->execute();
	}

	public function add() {
		$this->Crud->listener('relatedModels')->relatedModels([ 'Items' ]);
		$this->Crud->execute();
	}

	public function edit($id = null) {
		$this->Crud->on('beforeFind', function(Event $event) {
			$event->subject->query->contain([ 'Items' ]);
		});
		$this->Crud->listener('relatedModels')->relatedModels([ 'Items' ]);
		$this->Crud->execute();
	}

}
