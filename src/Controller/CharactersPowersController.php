<?php
namespace App\Controller;

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

}
