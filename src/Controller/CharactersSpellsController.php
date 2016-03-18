<?php
namespace App\Controller;

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

}
