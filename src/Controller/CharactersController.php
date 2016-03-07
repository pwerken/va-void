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

#		$this->mapMethod('add',           [ 'infobalie'       ]);
		$this->mapMethod('delete',        [ 'super'           ]);
		$this->mapMethod('edit',          [ 'infobalie'       ]);
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
		$this->set('parent', $this->Factions->get($id));
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

	protected function canDelete($entity)
	{
		$this->loadModel('CharactersConditions');
		$query = $this->CharactersConditions->find();
		$query->where(['character_id' => $entity->id]);
		if($query->count() > 0)
			return false;

		$this->loadModel('CharactersPowers');
		$query = $this->CharactersPowers->find();
		$query->where(['character_id' => $entity->id]);
		if($query->count() > 0)
			return false;

		$this->loadModel('CharactersSkills');
		$query = $this->CharactersSkills->find();
		$query->where(['character_id' => $entity->id]);
		if($query->count() > 0)
			return false;

		$this->loadModel('CharactersSpells');
		$query = $this->CharactersSpells->find();
		$query->where(['character_id' => $entity->id]);
		if($query->count() > 0)
			return false;

		$this->loadModel('Items');
		$query = $this->Items->find();
		$query->where(['character_id' => $entity->id]);
		if($query->count() > 0)
			return false;

		return true;
	}

}
