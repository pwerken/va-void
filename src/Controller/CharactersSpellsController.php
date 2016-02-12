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

	public function initialize() {
		parent::initialize();

		$this->Crud->mapAction('charactersIndex',
			[ 'className' => 'Crud.Index'
			, 'contain' => [ 'Characters', 'Spells' ]
			]);
		$this->Crud->mapAction('charactersView',
			[ 'className' => 'Crud.View'
			, 'contain' => [ 'Characters', 'Spells' ]
			]);
		$this->Crud->mapAction('charactersEdit',
			[ 'className' => 'Crud.Edit'
			, 'contain' => [ 'Characters', 'Spells' ]
			]);

		$this->Crud->mapAction('spellsIndex',
			[ 'className' => 'Crud.Index'
			, 'contain' => [ 'Characters', 'Spells' ]
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
	public function charactersView($plin, $chin, $id) {
		$this->Crud->on('beforeHandle', function(Event $event) {
			$event->subject->args = $this->argsCharId($event->subject->args);
		});
		return $this->Crud->execute();
	}
	public function charactersEdit($plin, $chin, $id) {
		return $this->charactersView($plin, $chin, $id);
	}

	public function spellsIndex($id) {
		$this->loadModel('Spells');
		$this->set('parent', $this->Spells->get($id));

		$this->Crud->on('beforePaginate',
			function(Event $event) use ($id) {
				$event->subject->query->where(['spell_id' => $id]);
		});
		return $this->Crud->execute();
	}
}
