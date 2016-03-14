<?php
namespace App\Controller;

use Cake\Event\Event;

class CharactersSpellsController
	extends AppController
{

	public function initialize()
	{
		parent::initialize();

		$contain = [ 'Characters', 'Spells' ];

		$this->mapMethod('charactersAdd',    [ 'infobalie'       ]);
		$this->mapMethod('charactersDelete', [ 'infobalie'       ]);
		$this->mapMethod('charactersEdit',   [ 'infobalie'       ]);
		$this->mapMethod('charactersIndex',  [ 'referee', 'user' ], $contain);
		$this->mapMethod('charactersView',   [ 'referee', 'user' ], $contain);

		$this->mapMethod('spellsIndex',      [ 'referee'         ], $contain);
	}

	public function charactersAdd($plin, $chin)
	{
		$this->loadModel('Characters');
		$parent = $this->Characters->plinChin($plin, $chin);
		$this->request->data('character_id', $parent->id);

		return $this->Crud->execute();
	}
	public function charactersIndex($plin, $chin)
	{
		$this->loadModel('Characters');
		$parent = $this->Characters->plinChin($plin, $chin);
		$this->set('parent', $parent);

		$this->Crud->on('beforePaginate',
			function(Event $event) use ($parent) {
				$event->subject->query->where(['character_id' => $parent->id]);
		});
		return $this->Crud->execute();
	}

	public function spellsIndex($id)
	{
		$this->loadModel('Spells');
		$this->set('parent', $this->Spells->get($id));

		$this->Crud->on('beforePaginate',
			function(Event $event) use ($id) {
				$event->subject->query->where(['spell_id' => $id]);
		});
		return $this->Crud->execute();
	}

}
