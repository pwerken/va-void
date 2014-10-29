<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

/**
 * CharactersSkills Controller
 *
 * @property App\Model\Table\CharactersSkillsTable $CharactersSkills
 */
class CharactersSkillsController extends AppController {

	public function index() {
		$this->Crud->on('beforePaginate', function(Event $event) {
			$this->paginate =
				[ 'contain' => [ 'Characters', 'Skills' ]
				];
		});
		return $this->Crud->execute();
	}

	public function view($id = null) {
		$this->Crud->on('beforeFind', function(Event $event) {
			$event->subject->query->contain([ 'Characters', 'Skills' ]);
		});
		return $this->Crud->execute();
	}

	public function add() {
		$this->Crud->listener('relatedModels')->relatedModels(
				[ 'Characters', 'Skills' ]);
		$this->Crud->execute();
	}

	public function edit($id = null) {
		$this->Crud->listener('relatedModels')->relatedModels(
				[ 'Characters', 'Skills' ]);
		$this->Crud->execute();
	}

}
