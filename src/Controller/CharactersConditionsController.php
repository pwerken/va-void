<?php
namespace App\Controller;

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

}
