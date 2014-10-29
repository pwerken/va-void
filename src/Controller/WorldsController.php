<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

/**
 * Worlds Controller
 *
 * @property App\Model\Table\WorldsTable $Worlds
 */
class WorldsController extends AppController {

	public function view($id = null) {
		$this->Crud->on('beforeFind', function(Event $event) {
			$event->subject->query->contain([ 'Characters' ]);
		});
		return $this->Crud->execute();
	}

	public function add() {
		$this->Crud->listener('relatedModels')->relatedModels([ 'Characters' ]);
		$this->Crud->execute();
	}

	public function edit($id = null) {
		$this->Crud->on('beforeFind', function(Event $event) {
			$event->subject->query->contain([ 'Characters' ]);
		});
		$this->Crud->listener('relatedModels')->relatedModels([ 'Characters' ]);
		return $this->Crud->execute();
	}

}
