<?php
namespace App\Controller;

class CharactersSpellsController
	extends AppController
{

	protected $touchRelation = [ 'Characters' => 'character_id'];

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

}
