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

		$contain = [ 'Characters', 'Attributes' ];

		$this->mapMethod('index', [ 'referee'         ], [ 'Characters' ]);
		$this->mapMethod('view',  [ 'referee', 'user' ], $contain);
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
