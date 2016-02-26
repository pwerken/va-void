<?php
namespace App\Controller;

use App\Controller\AppController;

class ConditionsController
	extends AppController
{

	public function initialize()
	{
		parent::initialize();

		$this->Crud->mapAction('index', 'Crud.Index');
		$this->Crud->mapAction('view',  'Crud.View');
	}

	public function isAuthorized($user)
	{
		switch($this->request->action) {
		case 'view':
			return $this->hasAuthUser() || $this->hasAuthReferee();
		case 'index':
			return $this->hasAuthReferee();
		default:
			return parent::isAuthorized($user);
		}
	}

	protected function hasAuthUser($id = null)
	{
		$coin = (int)$this->request->param('coin');
		$this->loadModel('CharactersConditions');
		$data = $this->CharactersConditions->find()
					->hydrate(false)
					->select(['Characters.player_id'])
					->where(['CharactersConditions.condition_id' => $coin])
					->contain('Characters')
					->first();
		return parent::hasAuthUser(@$data['Characters']['player_id'] ?: -1);
	}
}
