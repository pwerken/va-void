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

	public function initialize() {
		parent::initialize();

		$this->Crud->mapAction('characterIndex',
			[ 'className' => 'Crud.Index'
			, 'contain' => [ 'Characters', 'Conditions' ]
			]);
		$this->Crud->mapAction('characterView',
			[ 'className' => 'Crud.View'
			, 'contain' => [ 'Characters', 'Conditions' ]
			]);
		$this->Crud->mapAction('characterEdit',
			[ 'className' => 'Crud.Edit'
			, 'contain' => [ 'Characters', 'Conditions' ]
			]);

		$this->Crud->mapAction('conditionIndex',
			[ 'className' => 'Crud.Index'
			, 'contain' => [ 'Characters', 'Conditions' ]
			]);
	}

	public function characterIndex($plin, $chin) {
		$this->loadModel('Characters');
		$parent = $this->Characters->plinChin($plin, $chin);
		$id = $parent->id;

		$this->Crud->on('beforePaginate',
			function(Event $event) use ($id) {
				$event->subject->query->where(['character_id' => $id]);
		});
		$this->set('parent', $parent);
		return $this->Crud->execute();
	}
	public function characterView($plin, $chin, $coin) {
		$this->Crud->on('beforeHandle', function(Event $event) {
			$event->subject->args = $this->argsCharId($event->subject->args);
		});
		return $this->Crud->execute();
	}
	public function characterEdit($plin, $chin, $coin) {
		return $this->characterView($plin, $chin, $coin);
	}

	public function conditionIndex($coin) {
		$this->loadModel('Conditions');
		$parent = $this->Conditions->get($coin);

		$this->Crud->on('beforePaginate',
			function(Event $event) use ($coin) {
				$event->subject->query->where(['condition_id' => $coin]);
		});
		$this->set('parent', $parent);
		return $this->Crud->execute();
	}
}
