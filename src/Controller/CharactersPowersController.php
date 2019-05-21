<?php
namespace App\Controller;

use App\Utility\AuthState;

class CharactersPowersController
	extends AppController
{

	public function initialize()
	{
		parent::initialize();

		$this->loadComponent('QueueLammy');

		$this->mapMethod('charactersAdd',    [ 'referee'           ]);
		$this->mapMethod('charactersDelete', [ 'referee'           ]);
		$this->mapMethod('charactersEdit',   [ 'referee'           ]);
		$this->mapMethod('charactersIndex',  [ 'players'           ], true);
		$this->mapMethod('charactersView',   [ 'read-only', 'user' ], true);

		$this->mapMethod('powersIndex',      [ 'read-only', 'user' ], true);

		$this->Crud->mapAction('charactersQueue',
			[ 'className' => 'Crud.View'
			, 'auth' => [ 'referee' ]
			, 'findMethod' => 'withContain'
			]);
	}

	public function charactersAdd($char_id)
	{
		$this->request = $this->request->withData('character_id', $char_id);
		$this->Crud->execute();
	}

	public function charactersQueue($char_id, $poin)
	{
		$this->QueueLammy->execute();
	}

	public function powersIndex()
	{
		if(!AuthState::hasRole('read-only')) {
			$this->Crud->on('beforePaginate', function ($event) {
				$cond = ['Characters.player_id' => $this->Auth->user('id')];
				$event->getSubject()->query->where($cond);
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

		$poin = $this->request->getParam('poin');
		$data = $this->CharactersPowers->find()
					->enableHydration(false)
					->select(['player_id' => 'Characters.player_id'])
					->where(['CharactersPowers.power_id' => $poin])
					->contain('Characters')
					->first();

		return isset($data['player_id']) ? $data['player_id'] : NULL;
	}
}
