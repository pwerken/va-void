<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

/**
 * Skills Controller
 *
 * @property App\Model\Table\SkillsTable $Skills
 */
class SkillsController extends AppController {

	public function index() {
		$this->Crud->on('beforePaginate', function(Event $event) {
			$this->paginate =
				[ 'contain' => [ 'Manatypes' ]
				];
		});
		return $this->Crud->execute();
	}

	public function view($id = null) {
		$this->Crud->on('beforeFind', function(Event $event) {
			$event->subject->query->contain([ 'Manatypes', 'Characters' ]);
		});
		return $this->Crud->execute();
	}

	public function add() {
		$this->Crud->listener('relatedModels')->relatedModels(
				[ 'Manatypes', 'Characters' ]);
		$this->Crud->execute();
	}

	public function edit($id = null) {
		$this->Crud->on('beforeFind', function(Event $event) {
			$event->subject->query->contain([ 'Characters' ]);
		});
		$this->Crud->listener('relatedModels')->relatedModels(
				[ 'Manatypes', 'Characters' ]);
		return $this->Crud->execute();
	}

}
