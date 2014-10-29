<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

/**
 * CharactersConditions Controller
 *
 * @property App\Model\Table\CharactersConditionsTable $CharactersConditions
 */
class CharactersConditionsController extends AppController {

	public function index() {
		$this->Crud->on('beforePaginate', function(Event $event) {
			$this->paginate =
				[ 'contain' => [ 'Characters', 'Conditions' ]
				];
		});
		return $this->Crud->execute();
	}

	public function view($id = null) {
		$this->Crud->on('beforeFind', function(Event $event) {
			$event->subject->query->contain([ 'Characters', 'Conditions' ]);
		});
		return $this->Crud->execute();
	}

	public function add() {
		$this->Crud->listener('relatedModels')->relatedModels(
				[ 'Characters', 'Conditions' ]);
		$this->Crud->execute();
	}

	public function edit($id = null) {
		$this->Crud->listener('relatedModels')->relatedModels(
				[ 'Characters', 'Conditions' ]);
		$this->Crud->execute();
	}

}
