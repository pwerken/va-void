<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

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
		$this->Crud->mapAction('edit', 'Crud.Edit');

		$this->Crud->mapAction('believesIndex', 'Crud.Index');
		$this->Crud->mapAction('factionsIndex', 'Crud.Index');
		$this->Crud->mapAction('groupsIndex',   'Crud.Index');
		$this->Crud->mapAction('playersIndex',  'Crud.Index');
		$this->Crud->mapAction('worldsIndex',   'Crud.Index');

		$this->Crud->on('beforeHandle', function(Event $event) {
			$event->subject->args = $this->argsCharId($event->subject->args);
		});
	}

	public function believesIndex($id) {
		$this->Crud->on('beforePaginate',
			function(Event $event) use ($id) {
				$event->subject->query->where(['belief_id' => $id]);
		});
		$this->loadModel('Believes');
		$this->set('parent', $this->Believes->get($id));
		return $this->Crud->execute();
	}
	public function factionsIndex($id) {
		$this->Crud->on('beforePaginate',
			function(Event $event) use ($id) {
				$event->subject->query->where(['faction_id' => $id]);
		});
		$this->loadModel('Factions');
		$this->set('parent', $this->Faction->get($id));
		return $this->Crud->execute();
	}
	public function groupsIndex($id) {
		$this->Crud->on('beforePaginate',
			function(Event $event) use ($id) {
				$event->subject->query->where(['group_id' => $id]);
		});
		$this->loadModel('Groups');
		$this->set('parent', $this->Groups->get($id));
		return $this->Crud->execute();
	}
	public function playersIndex($id) {
		$this->Crud->on('beforePaginate',
			function(Event $event) use ($id) {
				$event->subject->query->where(['player_id' => $id]);
		});
		$this->loadModel('Players');
		$this->set('parent', $this->Players->get($id));
		return $this->Crud->execute();
	}
	public function worldsIndex($id) {
		$this->Crud->on('beforePaginate',
			function(Event $event) use ($id) {
				$event->subject->query->where(['world_id' => $id]);
		});
		$this->loadModel('Worlds');
		$this->set('parent', $this->Worlds->get($id));
		return $this->Crud->execute();
	}

	public function isAuthorized($user)
	{
		switch($this->request->action) {
		case 'view':
		case 'playersIndex':
			return $this->hasAuthReferee() || $this->hasAuthUser();
		case 'index':
		case 'edit':
		case 'believesIndex':
		case 'factionsIndex':
		case 'groupsIndex':
		case 'worldsIndex':
			return $this->hasAuthReferee();
		default:
			return parent::isAuthorized($user);
		}
	}
}
