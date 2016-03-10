<?php
namespace App\Controller;

use App\AuthState;
use App\Controller\AppController;

class ConditionsController
	extends AppController
{

	public function initialize()
	{
		parent::initialize();

		$contain = [ 'Characters' ];

		$this->mapMethod('add',    [ 'referee'         ]);
		$this->mapMethod('delete', [ 'super'           ]);
		$this->mapMethod('edit',   [ 'referee'         ]);
		$this->mapMethod('index',  [ 'referee'         ]);
		$this->mapMethod('view',   [ 'referee', 'user' ], $contain);
	}

	protected function canDelete($entity)
	{
		$this->loadModel('CharactersConditions');
		$query = $this->CharactersConditions->find();
		$query->where(['condition_id' => $entity->id]);
		return ($query->count() == 0);
	}

	protected function hasAuthUser($id = null)
	{
		$coin = $this->request->param('coin');
		$this->loadModel('CharactersConditions');
		$data = $this->CharactersConditions->find()
					->hydrate(false)
					->select(['player_id' => 'Characters.player_id'])
					->where(['CharactersConditions.condition_id' => $coin])
					->contain('Characters')
					->first();
		return parent::hasAuthUser(@$data['player_id']);
	}

}
