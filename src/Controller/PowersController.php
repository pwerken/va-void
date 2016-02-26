<?php
namespace App\Controller;

use App\Controller\AppController;

class PowersController
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
		$poin = (int)$this->request->param('poin');
		$this->loadModel('CharactersPowers');
		$data = $this->CharactersPowers->find()
					->hydrate(false)
					->select(['Characters.player_id'])
					->where(['CharactersPowers.power_id' => $poin])
					->contain('Characters')
					->first();
		return parent::hasAuthUser(@$data['Characters']['player_id'] ?: -1);
	}
}
