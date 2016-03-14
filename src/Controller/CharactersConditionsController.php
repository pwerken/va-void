<?php
namespace App\Controller;

use Cake\Event\Event;

class CharactersConditionsController
	extends AppController
{

	public function initialize()
	{
		parent::initialize();

		$contain = [ 'Characters', 'Conditions' ];

		$this->mapMethod('charactersAdd',    [ 'referee'         ]);
		$this->mapMethod('charactersDelete', [ 'referee'         ]);
		$this->mapMethod('charactersEdit',   [ 'referee'         ]);
		$this->mapMethod('charactersIndex',  [ 'referee', 'user' ], $contain);
		$this->mapMethod('charactersView',   [ 'referee', 'user' ], $contain);

		$this->mapMethod('conditionsIndex',  [ 'referee'         ], $contain);
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

	public function conditionsIndex($coin)
	{
		$this->loadModel('Conditions');
		$this->set('parent', $this->Conditions->get($coin));

		$this->Crud->on('beforePaginate',
			function(Event $event) use ($coin) {
				$event->subject->query->where(['condition_id' => $coin]);
		});
		return $this->Crud->execute();
	}

}
