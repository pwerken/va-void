<?php
declare(strict_types=1);

namespace App\Controller;

use App\Utility\AuthState;

class CharactersConditionsController
	extends AppController
{

	public function initialize(): void
	{
		parent::initialize();

		$this->loadComponent('QueueLammy');

		$this->mapMethod('charactersAdd',    [ 'referee'           ]);
		$this->mapMethod('charactersDelete', [ 'referee'           ]);
		$this->mapMethod('charactersEdit',   [ 'referee'           ]);
		$this->mapMethod('charactersIndex',  [ 'players'           ], true);
		$this->mapMethod('charactersView',   [ 'read-only', 'user' ], true);

		$this->mapMethod('conditionsIndex',  [ 'read-only', 'user' ], true);

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

	public function charactersQueue($char_id, $coin)
	{
		$this->QueueLammy->execute();
	}

	public function conditionsIndex()
	{
		if(!AuthState::hasRole('read-only')) {
			$this->Crud->on('beforePaginate', function ($event) {
				$cond = ['Characters.player_id' => $this->Auth->user('id')];
				$event->getSubject()->query->where($cond);
			});
		}

		$this->Crud->execute();
	}

	protected function wantAuthUser(): ?int
	{
		$plin = parent::wantAuthUser();
		if (!is_null($plin))
			return $plin;

		$coin = $this->request->getParam('coin');
		$data = $this->CharactersConditions->find()
					->enableHydration(false)
					->select(['player_id' => 'Characters.player_id'])
					->where(['CharactersConditions.condition_id' => $coin])
					->contain('Characters')
					->first();

		return isset($data['player_id']) ? $data['player_id'] : NULL;
	}
}
