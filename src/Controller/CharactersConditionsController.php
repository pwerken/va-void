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

		$this->Crud->mapAction('charactersIndex',
			[ 'className' => 'Crud.Index'
			, 'contain' => [ 'Characters', 'Conditions' ]
			]);
		$this->Crud->mapAction('charactersAdd',
			[ 'className' => 'Crud.Add'
			]);
		$this->Crud->mapAction('charactersDelete',
			[ 'className' => 'Crud.Delete'
			]);
		$this->Crud->mapAction('charactersView',
			[ 'className' => 'Crud.View'
			, 'contain' => [ 'Characters', 'Conditions' ]
			]);
		$this->Crud->mapAction('charactersEdit',
			[ 'className' => 'Crud.Edit'
			, 'contain' => [ 'Characters', 'Conditions' ]
			]);

		$this->Crud->mapAction('conditionsIndex',
			[ 'className' => 'Crud.Index'
			, 'contain' => [ 'Characters', 'Conditions' ]
			]);
	}

	public function charactersIndex($plin, $chin) {
		$this->loadModel('Characters');
		$parent = $this->Characters->plinChin($plin, $chin);
		$this->set('parent', $parent);

		$this->Crud->on('beforePaginate',
			function(Event $event) use ($parent) {
				$event->subject->query->where(['character_id' => $parent->id]);
		});
		return $this->Crud->execute();
	}
	public function charactersAdd($plin, $chin) {
		$this->loadModel('Characters');
		$parent = $this->Characters->plinChin($plin, $chin);
		$this->request->data['character_id'] = $parent->id;

		return $this->Crud->execute();
	}
	public function charactersDelete($plin, $chin, $coin) {
		return $this->charactersView($plin, $chin, $coin);
	}
	public function charactersView($plin, $chin, $coin) {
		$this->Crud->on('beforeHandle', function(Event $event) {
			$event->subject->args = $this->argsCharId($event->subject->args);
		});
		return $this->Crud->execute();
	}
	public function charactersEdit($plin, $chin, $coin) {
		return $this->charactersView($plin, $chin, $coin);
	}

	public function conditionsIndex($coin) {
		$this->loadModel('Conditions');
		$this->set('parent', $this->Conditions->get($coin);

		$this->Crud->on('beforePaginate',
			function(Event $event) use ($coin) {
				$event->subject->query->where(['condition_id' => $coin]);
		});
		return $this->Crud->execute();
	}
}
