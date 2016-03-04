<?php
namespace App\Controller;

use App\Controller\AppController;

class PowersController
	extends AppController
{

	public function initialize()
	{
		parent::initialize();

		$this->mapMethod('index', [ 'referee'         ]);
		$this->mapmethod('view',  [ 'referee', 'user' ]);
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
