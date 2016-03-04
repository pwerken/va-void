<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

class CharactersController
	extends AppController
{

	public function initialize()
	{
		parent::initialize();

		$contain =  [ 'Believes', 'Factions', 'Groups', 'Players', 'Worlds'
					, 'Items', 'Skills' => [ 'Manatypes' ]
					, 'Conditions', 'Powers', 'Spells'
					];

		$this->mapMethod('edit',          [ 'referee'         ]);
		$this->mapMethod('index',         [ 'referee'         ]);
		$this->mapMethod('view',          [ 'referee', 'user' ], $contain);

		$this->mapMethod('believesIndex', [ 'referee'         ]);
		$this->mapMethod('factionsIndex', [ 'referee'         ]);
		$this->mapMethod('groupsIndex',   [ 'referee'         ]);
		$this->mapMethod('playersIndex',  [ 'referee', 'user' ]);
		$this->mapMethod('worldsIndex',   [ 'referee'         ]);

		$this->Crud->on('beforeHandle', function(Event $event) {
			$event->subject->args = $this->argsCharId($event->subject->args);
		});
	}

	public function believesIndex($id)
	{
		$this->Crud->on('beforePaginate',
			function(Event $event) use ($id) {
				$event->subject->query->where(['belief_id' => $id]);
		});
		$this->loadModel('Believes');
		$this->set('parent', $this->Believes->get($id));
		return $this->Crud->execute();
	}
	public function factionsIndex($id)
	{
		$this->Crud->on('beforePaginate',
			function(Event $event) use ($id) {
				$event->subject->query->where(['faction_id' => $id]);
		});
		$this->loadModel('Factions');
		$this->set('parent', $this->Faction->get($id));
		return $this->Crud->execute();
	}
	public function groupsIndex($id)
	{
		$this->Crud->on('beforePaginate',
			function(Event $event) use ($id) {
				$event->subject->query->where(['group_id' => $id]);
		});
		$this->loadModel('Groups');
		$this->set('parent', $this->Groups->get($id));
		return $this->Crud->execute();
	}
	public function playersIndex($id)
	{
		$this->Crud->on('beforePaginate',
			function(Event $event) use ($id) {
				$event->subject->query->where(['player_id' => $id]);
		});
		$this->loadModel('Players');
		$this->set('parent', $this->Players->get($id));
		return $this->Crud->execute();
	}
	public function worldsIndex($id)
	{
		$this->Crud->on('beforePaginate',
			function(Event $event) use ($id) {
				$event->subject->query->where(['world_id' => $id]);
		});
		$this->loadModel('Worlds');
		$this->set('parent', $this->Worlds->get($id));
		return $this->Crud->execute();
	}
}
