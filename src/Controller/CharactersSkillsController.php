<?php
namespace App\Controller;

class CharactersSkillsController
	extends AppController
{

	protected $touchRelation = [ 'Characters' => 'character_id'];

	public function initialize()
	{
		parent::initialize();

		$this->mapMethod('charactersAdd',    [ 'infobalie'       ]);
		$this->mapMethod('charactersDelete', [ 'infobalie'       ]);
# There are no properties on this relation to edit
#		$this->mapMethod('charactersEdit',   [ 'infobalie'       ]);
		$this->mapMethod('charactersIndex',  [ 'referee'         ], true);
		$this->mapMethod('charactersView',   [ 'referee', 'user' ], true);

		$this->mapMethod('skillsIndex',      [ 'referee'         ], true);
	}

}
