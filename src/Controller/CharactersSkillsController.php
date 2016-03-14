<?php
namespace App\Controller;

use Cake\Event\Event;

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

	public function skillsIndex($id)
	{
		$this->loadModel('Skills');
		$this->set('parent', $this->Skills->get($id));

		$this->Crud->on('beforePaginate',
			function(Event $event) use ($id) {
				$event->subject->query->where(['skill_id' => $id]);
		});
		return $this->Crud->execute();
	}

}
