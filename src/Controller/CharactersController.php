<?php
namespace App\Controller;

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

		$this->mapMethod('add',           [ 'infobalie'       ]);
		$this->mapMethod('delete',        [ 'super'           ]);
		$this->mapMethod('edit',          [ 'referee'         ]);
		$this->mapMethod('index',         [ 'referee'         ]);
		$this->mapMethod('view',          [ 'referee', 'user' ], $contain);

		$this->mapMethod('believesIndex', [ 'referee'         ]);
		$this->mapMethod('factionsIndex', [ 'referee'         ]);
		$this->mapMethod('groupsIndex',   [ 'referee'         ]);
		$this->mapMethod('playersIndex',  [ 'referee', 'user' ]);
		$this->mapMethod('worldsIndex',   [ 'referee'         ]);
	}

	public function add($plin)
	{
		$plin = $plin ?: $this->request->data('plin');
		$this->request->data('player_id', $plin);
		unset($this->request->data['plin']);

		$next = $this->Characters->find()
					->select(['nextChin' => 'MAX(chin)'])
					->where(['player_id' => $plin])
					->hydrate(false)
					->first();
		$next = $next ? $next['nextChin'] + 1: 1;
		$this->request->data('chin', $this->request->data('chin') ?: $next);

		$this->Crud->execute();
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

	public function CrudBeforeHandle(Event $event)
	{
		$event->subject->args = $this->argsCharId($event->subject->args);
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
