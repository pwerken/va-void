<?php
namespace App\Controller;

class CharactersConditionsController
	extends AppController
{

	public function initialize()
	{
		parent::initialize();

		$this->mapMethod('charactersAdd',    [ 'referee'           ]);
		$this->mapMethod('charactersDelete', [ 'referee'           ]);
		$this->mapMethod('charactersEdit',   [ 'referee'           ]);
		$this->mapMethod('charactersIndex',  [ 'players'           ], true);
		$this->mapMethod('charactersView',   [ 'read-only', 'user' ], true);

		$this->mapMethod('conditionsIndex',  [ 'read-only', 'user' ], true);

		$this->Crud->mapAction('charactersQueue',
			[ 'className' => 'Crud.View'
			, 'auth' => [ 'infobalie' ]
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
		$this->queueLammy();
	}

	public function conditionsIndex()
	{
		if(!$this->hasAuth('read-only')) {
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
