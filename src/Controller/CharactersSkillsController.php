<?php
namespace App\Controller;

class CharactersSkillsController
	extends AppController
{

	public function initialize()
	{
		parent::initialize();

		$contain = [ 'Characters', 'Skills' => [ 'Manatypes' ] ];

		$this->mapMethod('charactersAdd',    [ 'infobalie'       ]);
		$this->mapMethod('charactersDelete', [ 'infobalie'       ]);
# There are no properties on this relation to edit
#		$this->mapMethod('charactersEdit',   [ 'infobalie'       ]);
		$this->mapMethod('charactersIndex',  [ 'referee'         ], $contain);
		$this->mapMethod('charactersView',   [ 'referee', 'user' ], $contain);

		$this->mapMethod('skillsIndex',      [ 'referee'         ], $contain);
	}

	public function charactersAdd($plin, $chin)
	{
		$this->loadModel('Characters');
		$parent = $this->Characters->plinChin($plin, $chin);
		$this->request->data('character_id', $parent->id);

		return $this->Crud->execute();
	}

}
