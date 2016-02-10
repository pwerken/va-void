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

	public function initialize() {
		parent::initialize();

		$this->Crud->mapAction('characterIndex',
			[ 'className' => 'Crud.Index'
			, 'contain' => [ 'Characters', 'Skills' => [ 'Manatypes' ] ]
			]);
		$this->Crud->mapAction('characterView',
			[ 'className' => 'Crud.View'
			, 'contain' => [ 'Characters', 'Skills' => [ 'Manatypes' ] ]
			]);
		$this->Crud->mapAction('characterEdit',
			[ 'className' => 'Crud.Edit'
			, 'contain' => [ 'Characters', 'Skills' => [ 'Manatypes' ] ]
			]);

		$this->Crud->mapAction('skillIndex',
			[ 'className' => 'Crud.Index'
			, 'contain' => [ 'Characters', 'Skills' => [ 'Manatypes' ] ]
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

	public function skillIndex($id) {
		$this->loadModel('Skills');
		$parent = $this->Skills->get($id);

		$this->Crud->on('beforePaginate',
			function(Event $event) use ($id) {
				$event->subject->query->where(['skill_id' => $id]);
		});
		$this->set('parent', $parent);
		return $this->Crud->execute();
	}
}
