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

	public function initialize() {
		parent::initialize();

		$this->Crud->mapAction('characterIndex',
			[ 'className' => 'Crud.Index'
			, 'contain' => [ 'Characters', 'Powers' ]
			]);
		$this->Crud->mapAction('characterView',
			[ 'className' => 'Crud.View'
			, 'contain' => [ 'Characters', 'Powers' ]
			]);
		$this->Crud->mapAction('characterEdit',
			[ 'className' => 'Crud.Edit'
			, 'contain' => [ 'Characters', 'Powers' ]
			]);

		$this->Crud->mapAction('powerIndex',
			[ 'className' => 'Crud.Index'
			, 'contain' => [ 'Characters', 'Powers' ]
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
	public function characterView($plin, $chin, $poin) {
		$this->Crud->on('beforeHandle', function(Event $event) {
			$event->subject->args = $this->argsCharId($event->subject->args);
		});
		return $this->Crud->execute();
	}
	public function characterEdit($plin, $chin, $poin) {
		return $this->characterView($plin, $chin, $poin);
	}

	public function powerIndex($poin) {
		$this->loadModel('Powers');
		$parent = $this->Powers->get($poin);
		$this->Crud->on('beforePaginate',
			function(Event $event) use ($poin) {
				$event->subject->query->where(['power_id' => $poin]);
		});
		$this->set('parent', $parent);
		return $this->Crud->execute();
	}
}
