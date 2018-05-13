<?php
namespace App\Controller;

class CharactersSpellsController
	extends AppController
{

	public function initialize()
	{
		parent::initialize();

		$this->mapMethod('charactersAdd',    [ 'referee'           ]);
		$this->mapMethod('charactersDelete', [ 'referee'           ]);
		$this->mapMethod('charactersEdit',   [ 'referee'           ]);
		$this->mapMethod('charactersIndex',  [ 'read-only', 'user' ], true);
		$this->mapMethod('charactersView',   [ 'read-only', 'user' ], true);

		$this->mapMethod('spellsIndex',      [ 'read-only'         ], true);
	}

	public function charactersAdd($character_id)
	{
		$this->request->data('character_id', $character_id);
		$this->Crud->execute();
	}

}
