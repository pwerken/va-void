<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Crud\Event\Subject;

/**
 * Characters Controller
 *
 * @property App\Model\Table\CharactersTable $Characters
 */
class CharactersController extends AppController {

	public function initialize() {
		parent::initialize();

		$this->Crud->mapAction('index', 'Crud.Index');
		$this->Crud->mapAction('view',
			[ 'className' => 'Crud.View'
			, 'contain' =>
				[ 'Believes'
				, 'Conditions'
				, 'Factions'
				, 'Groups'
				, 'Items'
				, 'Players'
				, 'Powers'
				, 'Skills' => [ 'Manatypes' ]
				, 'Spells'
				, 'Worlds'
			]	]);
		$this->Crud->mapAction('edit',
			[ 'className' => 'Crud.Edit'
			, 'contain' =>
				[ 'Conditions'
				, 'Powers'
				, 'Skills'
				, 'Spells'
				]
			, 'relatedModels' =>
				[ 'Believes'
				, 'Conditions'
				, 'Factions'
				, 'Groups'
				, 'Players'
				, 'Powers'
				, 'Skills'
				, 'Spells'
				, 'Worlds'
			]	]);

		$this->Crud->mapAction('beliefIndex',  'Crud.Index');
		$this->Crud->mapAction('factionIndex', 'Crud.Index');
		$this->Crud->mapAction('groupIndex',   'Crud.Index');
		$this->Crud->mapAction('playerIndex',  'Crud.Index');
		$this->Crud->mapAction('worldIndex',   'Crud.Index');

		$this->Crud->on('beforeHandle', function(Event $event) {
			$event->subject->args = $this->argsCharId($event->subject->args);
		});
	}

	public function beliefIndex($id) {
		$this->Crud->on('beforePaginate',
			function(Event $event) use ($id) {
				$event->subject->query->where(['belief_id' => $id]);
		});
		$this->loadModel('Believes');
		$this->set('parent', $this->Believes->get($id));
		return $this->Crud->execute();
	}
	public function factionIndex($id) {
		$this->Crud->on('beforePaginate',
			function(Event $event) use ($id) {
				$event->subject->query->where(['faction_id' => $id]);
		});
		$this->loadModel('Factions');
		$this->set('parent', $this->Faction->get($id));
		return $this->Crud->execute();
	}
	public function groupIndex($id) {
		$this->Crud->on('beforePaginate',
			function(Event $event) use ($id) {
				$event->subject->query->where(['group_id' => $id]);
		});
		$this->loadModel('Groups');
		$this->set('parent', $this->Groups->get($id));
		return $this->Crud->execute();
	}
	public function playerIndex($id) {
		$this->Crud->on('beforePaginate',
			function(Event $event) use ($id) {
				$event->subject->query->where(['player_id' => $id]);
		});
		$this->loadModel('Players');
		$this->set('parent', $this->Players->get($id));
		return $this->Crud->execute();
	}
	public function worldIndex($id) {
		$this->Crud->on('beforePaginate',
			function(Event $event) use ($id) {
				$event->subject->query->where(['world_id' => $id]);
		});
		$this->loadModel('Worlds');
		$this->set('parent', $this->Worlds->get($id));
		return $this->Crud->execute();
	}
}
