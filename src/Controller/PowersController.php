<?php
namespace App\Controller;

class PowersController
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
		$this->mapmethod('view',   [ 'referee', 'user' ], $contain);
	}

	protected function hasAuthUser($id = null)
	{
		$poin = $this->request->param('poin');
		$this->loadModel('CharactersPowers');
		$data = $this->CharactersPowers->find()
					->hydrate(false)
					->select(['player_id' => 'Characters.player_id'])
					->where(['CharactersPowers.power_id' => $poin])
					->contain('Characters')
					->first();
		return parent::hasAuthUser(@$data['player_id']);
	}

}
