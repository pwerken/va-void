<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

class CharactersSkillsController
	extends AppController
{

	public function initialize()
	{
		parent::initialize();

		$this->Crud->mapAction('charactersAdd',    'Crud.Add');
		$this->Crud->mapAction('charactersDelete', 'Crud.Delete');
		$this->Crud->mapAction('charactersIndex',
			[ 'className' => 'Crud.Index'
			, 'contain' => [ 'Characters', 'Skills' => [ 'Manatypes' ] ]
			]);
		$this->Crud->mapAction('charactersView',
			[ 'className' => 'Crud.View'
			, 'contain' => [ 'Characters', 'Skills' => [ 'Manatypes' ] ]
			]);

		$this->Crud->mapAction('skillsIndex',
			[ 'className' => 'Crud.Index'
			, 'contain' => [ 'Characters', 'Skills' => [ 'Manatypes' ] ]
			]);
	}

	public function charactersAdd($plin, $chin)
	{
		$this->loadModel('Characters');
		$parent = $this->Characters->plinChin($plin, $chin);
		$this->request->data['character_id'] = $parent->id;

		return $this->Crud->execute();
	}
	public function charactersDelete($plin, $chin, $id)
	{
		return $this->charactersView($plin, $chin, $id);
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
	public function charactersView($plin, $chin, $id)
	{
		$this->Crud->on('beforeHandle', function(Event $event) {
			$event->subject->args = $this->argsCharId($event->subject->args);
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

	public function isAuthorized($user)
	{
		switch($this->request->action) {
		case 'charactersIndex':
		case 'charactersView':
			return $this->hasAuthUser() || $this->hasAuthReferee();
		case 'charactersAdd':
		case 'charactersDelete':
			return $this->hasAuthInfobalie();
		case 'skillsIndex':
			return $this->hasAuthReferee();
		default:
			return parent::isAuthorized($user);
		}
	}
}
