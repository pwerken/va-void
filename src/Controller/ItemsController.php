<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

class ItemsController
	extends AppController
{

	public function initialize()
	{
		parent::initialize();

		$this->Crud->mapAction('index',
			[ 'className' => 'Crud.Index'
			, 'contain' => [ 'Characters' ]
			]);
		$this->Crud->mapAction('view',
			[ 'className' => 'Crud.View'
			, 'contain' => [ 'Characters', 'Attributes' ]
			]);
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
		$itin = (int)$this->request->param('itin');
		$data = $this->Items->find()
					->hydrate(false)
					->select(['Characters.player_id'])
					->where(['Items.id' => $itin])
					->contain('Characters')
					->first();
		return parent::hasAuthUser(@$data['Characters']['player_id'] ?: -1);
	}
}
