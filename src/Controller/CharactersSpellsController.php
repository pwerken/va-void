<?php
namespace App\Controller;

class CharactersSpellsController
	extends AppController
{

	public function initialize()
	{
		parent::initialize();

		$this->mapMethod('charactersAdd',    [ 'infobalie'       ]);
		$this->mapMethod('charactersDelete', [ 'infobalie'       ]);
		$this->mapMethod('charactersEdit',   [ 'infobalie'       ]);
		$this->mapMethod('charactersIndex',  [ 'referee', 'user' ], true);
		$this->mapMethod('charactersView',   [ 'referee', 'user' ], true);

		$this->mapMethod('spellsIndex',      [ 'referee'         ], true);
	}

	public function charactersAdd($character_id)
	{
		$this->request->data('character_id', $character_id);
		$this->Crud->execute();
	}

}
