<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

/**
 * CharactersSpells Controller
 *
 * @property App\Model\Table\CharactersSpellsTable $CharactersSpells
 */
class CharactersSpellsController extends AppController {

	public function index() {
		$this->Crud->on('beforePaginate', function(Event $event) {
			$this->paginate =
				[ 'contain' => [ 'Characters', 'Spells' ]
				];
		});
		return $this->Crud->execute();
	}

	public function view($id = null) {
		$this->Crud->on('beforeFind', function(Event $event) {
			$event->subject->query->contain([ 'Characters', 'Spells' ]);
		});
		return $this->Crud->execute();
	}

	public function add() {
		$this->Crud->listener('relatedModels')->relatedModels(
				[ 'Characters', 'Spells' ]);
		$this->Crud->execute();
	}

	public function edit($id = null) {
		$this->Crud->listener('relatedModels')->relatedModels(
				[ 'Characters', 'Spells' ]);
		$this->Crud->execute();
	}

}
