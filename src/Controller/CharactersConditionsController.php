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

		$this->Crud->mapAction('conditionIndex',
			[ 'className' => 'Crud.Index'
			, 'contain' => [ 'Characters', 'Conditions' ]
			]);
		$this->Crud->mapAction('conditionView',
			[ 'className' => 'Crud.View'
			, 'contain' => [ 'Characters', 'Conditions' ]
			]);
	}

	public function characterIndex($plin, $chin) {
		$this->Crud->on('beforePaginate',
			function(Event $event) use ($plin, $chin) {
				$this->loadModel('Characters');
				$event->subject->query->where(['character_id' =>
					$this->Characters->plinChin($plin, $chin)]);
		});
		return $this->Crud->execute();
	}

	public function characterView($plin, $chin, $coin) {
		$this->Crud->on('beforeHandle', function(\Cake\Event\Event $event) {
			$event->subject->args = $this->argsCharId($event->subject->args);
		});
		return $this->Crud->execute();
	}

	public function conditionIndex($coin) {
		$this->Crud->on('beforePaginate',
			function(Event $event) use ($coin) {
				$event->subject->query->where(['condition_id' => $coin]);
		});
		return $this->Crud->execute();
	}

	public function conditionView($coin, $plin, $chin) {
		$this->Crud->on('beforeHandle', function(\Cake\Event\Event $event) {
			$args = $this->argsOrder("cab", "abc", $event->subject->args);
			$args = $this->argsCharId($args);
			$event->subject->args = $args;
		});
		return $this->Crud->execute();
	}
}
