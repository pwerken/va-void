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

		$this->Crud->mapAction('charactersIndex',
			[ 'className' => 'Crud.Index'
			, 'contain' => [ 'Characters', 'Powers' ]
			]);
		$this->Crud->mapAction('charactersAdd',
			[ 'className' => 'Crud.Add'
			]);
		$this->Crud->mapAction('charactersDelete',
			[ 'className' => 'Crud.Delete'
			]);
		$this->Crud->mapAction('charactersView',
			[ 'className' => 'Crud.View'
			, 'contain' => [ 'Characters', 'Powers' ]
			]);
		$this->Crud->mapAction('charactersEdit',
			[ 'className' => 'Crud.Edit'
			, 'contain' => [ 'Characters', 'Powers' ]
			]);

		$this->Crud->mapAction('powersIndex',
			[ 'className' => 'Crud.Index'
			, 'contain' => [ 'Characters', 'Powers' ]
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
	public function charactersDelete($plin, $chin, $poin) {
		return $this->charactersView($plin, $chin, $poin);
	}
	public function charactersView($plin, $chin, $poin) {
		$this->Crud->on('beforeHandle', function(Event $event) {
			$event->subject->args = $this->argsCharId($event->subject->args);
		});
		return $this->Crud->execute();
	}
	public function charactersEdit($plin, $chin, $poin) {
		return $this->charactersView($plin, $chin, $poin);
	}

	public function powersIndex($poin) {
		$this->loadModel('Powers');
		$this->set('parent', $this->Powers->get($poin));

		$this->Crud->on('beforePaginate',
			function(Event $event) use ($poin) {
				$event->subject->query->where(['power_id' => $poin]);
		});
		return $this->Crud->execute();
	}
}
