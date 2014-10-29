<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

/**
 * CharactersPowers Controller
 *
 * @property App\Model\Table\CharactersPowersTable $CharactersPowers
 */
class CharactersPowersController extends AppController {

	public function index() {
		$this->Crud->on('beforePaginate', function(Event $event) {
			$this->paginate =
				[ 'contain' => [ 'Characters', 'Powers' ]
				];
		});
		return $this->Crud->execute();
	}

	public function view($id = null) {
		$this->Crud->on('beforeFind', function(Event $event) {
			$event->subject->query->contain([ 'Characters', 'Powers' ]);
		});
		return $this->Crud->execute();
	}

	public function add() {
		$this->Crud->listener('relatedModels')->relatedModels(
				[ 'Characters', 'Powers' ]);
		$this->Crud->execute();
	}

	public function edit($id = null) {
		$this->Crud->listener('relatedModels')->relatedModels(
				[ 'Characters', 'Powers' ]);
		$this->Crud->execute();
	}

}
