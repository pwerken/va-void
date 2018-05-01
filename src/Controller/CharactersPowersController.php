<?php
namespace App\Controller;

class CharactersPowersController
	extends AppController
{

	public function initialize()
	{
		parent::initialize();

		$this->mapMethod('charactersAdd',    [ 'referee'         ]);
		$this->mapMethod('charactersDelete', [ 'referee'         ]);
		$this->mapMethod('charactersEdit',   [ 'referee'         ]);
		$this->mapMethod('charactersIndex',  [ 'players'         ], true);
		$this->mapMethod('charactersView',   [ 'referee', 'user' ], true);

		$this->mapMethod('powersIndex',      [ 'referee', 'user' ], true);

		$this->Crud->mapAction('charactersQueue',
			[ 'className' => 'Crud.View'
			, 'auth' => [ 'infobalie' ]
			, 'findMethod' => 'withContain'
			]);
	}

	public function charactersAdd($character_id)
	{
		$this->request->data('character_id', $character_id);
		$this->Crud->execute();
	}

	public function charactersQueue($character_id, $poin)
	{
		$this->queueLammy();
	}

	public function powersIndex()
	{
		if(!$this->hasAuth('referee')) {
			$this->Crud->on('beforePaginate', function ($event) {
				$cond = ['Characters.player_id' => $this->Auth->user('id')];
				$event->subject()->query->where($cond);
			});
		}

		$this->Crud->execute();
	}

	protected function wantAuthUser()
	{
		$plin = parent::wantAuthUser();
		if($plin !== false) {
			return $plin;
		}

		$poin = $this->request->param('poin');
		$data = $this->CharactersPowers->find()
					->hydrate(false)
					->select(['player_id' => 'Characters.player_id'])
					->where(['CharactersPowers.power_id' => $poin])
					->contain('Characters')
					->first();

		return isset($data['player_id']) ? $data['player_id'] : NULL;
	}
}
