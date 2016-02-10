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

		$this->Crud->mapAction('characterIndex',
			[ 'className' => 'Crud.Index'
			, 'contain' => [ 'Characters', 'Spells' ]
			]);
		$this->Crud->mapAction('characterView',
			[ 'className' => 'Crud.View'
			, 'contain' => [ 'Characters', 'Spells' ]
			]);
		$this->Crud->mapAction('characterEdit',
			[ 'className' => 'Crud.Edit'
			, 'contain' => [ 'Characters', 'Spells' ]
			]);

		$this->Crud->mapAction('spellIndex',
			[ 'className' => 'Crud.Index'
			, 'contain' => [ 'Characters', 'Spells' ]
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
	public function characterView($plin, $chin, $id) {
		$this->Crud->on('beforeHandle', function(Event $event) {
			$event->subject->args = $this->argsCharId($event->subject->args);
		});
		return $this->Crud->execute();
	}
	public function characterEdit($plin, $chin, $id) {
		return $this->characterView($plin, $chin, $id);
	}

	public function spellIndex($id) {
		$this->loadModel('Spells');
		$parent = $this->Spells->get($id);

		$this->Crud->on('beforePaginate',
			function(Event $event) use ($id) {
				$event->subject->query->where(['spell_id' => $id]);
		});
		$this->set('parent', $parent);
		return $this->Crud->execute();
	}
}
