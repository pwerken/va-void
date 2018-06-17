<?php
namespace App\Controller;

class CharactersSkillsController
	extends AppController
{

	public function initialize()
	{
		parent::initialize();

		$this->mapMethod('charactersAdd',    [ 'referee'           ]);
		$this->mapMethod('charactersDelete', [ 'referee'           ]);
# There are no properties on this relation to edit
#		$this->mapMethod('charactersEdit',   [ 'infobalie'         ]);
		$this->mapMethod('charactersIndex',  [ 'read-only', 'user' ], true);
		$this->mapMethod('charactersView',   [ 'read-only', 'user' ], true);

		$this->mapMethod('skillsIndex',      [ 'read-only'         ], true);
	}

	public function charactersAdd($char_id)
	{
		$this->request = $this->request->withData('character_id', $char_id);
		$this->Crud->execute();
	}

}
