<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

class CharactersPowersController
	extends AppController
{

	public function initialize()
	{
		parent::initialize();

		$contain = [ 'Characters', 'Powers' ];

		$this->mapMethod('charactersAdd',    [ 'referee'         ]);
		$this->mapMethod('charactersDelete', [ 'referee'         ]);
		$this->mapMethod('charactersEdit',   [ 'referee'         ], $contain);
		$this->mapMethod('charactersIndex',  [ 'referee', 'user' ], $contain);
		$this->mapMethod('charactersView',   [ 'referee'         ], $contain);

		$this->mapMethod('powersIndex',      [ 'referee'         ], $contain);
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

	public function powersIndex($poin)
	{
		$this->loadModel('Powers');
		$this->set('parent', $this->Powers->get($poin));

		$this->Crud->on('beforePaginate',
			function(Event $event) use ($poin) {
				$event->subject->query->where(['power_id' => $poin]);
		});
		return $this->Crud->execute();
	}

}
