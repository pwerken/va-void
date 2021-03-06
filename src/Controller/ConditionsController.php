<?php
namespace App\Controller;

use App\Utility\AuthState;

class ConditionsController
	extends AppController
{

	protected $searchFields =
		[ 'Conditions.name'
		, 'Conditions.player_text'
		, 'Conditions.referee_notes'
		, 'Conditions.notes'
		];

	public function initialize()
	{
		parent::initialize();

		$this->loadComponent('QueueLammy');

		$this->mapMethod('add',    [ 'referee'           ]);
		$this->mapMethod('delete', [ 'super'             ]);
		$this->mapMethod('edit',   [ 'referee'           ]);
		$this->mapMethod('index',  [ 'players'           ]);
		$this->mapMethod('view',   [ 'read-only', 'user' ], true);

		$this->Crud->mapAction('queue',
			[ 'className' => 'Crud.View'
			, 'auth' => [ 'referee' ]
			, 'findMethod' => 'withContain'
			]);
	}

	public function index()
	{
		if($this->setResponseModified())
			return $this->response;

		$query = $this->Conditions->find()
					->select(['Conditions.id', 'Conditions.name'], true);

		if(!AuthState::hasRole('read-only')) {
			$plin = $this->Auth->user('id');
			$query->where(["Characters.player_id = $plin"])
					->leftJoin(['CharactersConditions' => 'characters_conditions'],
						    ['CharactersConditions.condition_id = Conditions.id'])
					->leftJoin(['Characters' => 'characters'],
						    ['Characters.id = CharactersConditions.character_id']);
		}

		$this->doRawIndex($query, 'Condition', '/conditions/', 'coin');
	}

	public function view($coin)
	{
		if(!AuthState::hasRole('read-only')) {
			$this->Crud->on('beforeFind', function ($event) {
				$cond = ['Characters.player_id' => $this->Auth->user('id')];
				$event->getSubject()->query->contain(
					['CharactersConditions' => function ($query) use ($cond)
						{
							return $query->where($cond);
						}
					]);
			});
		}

		$this->Crud->execute();
	}

	public function queue($coin)
	{
		$this->QueueLammy->execute();
	}

	protected function wantAuthUser()
	{
		$plin = parent::wantAuthUser();
		if($plin !== false) {
			return $plin;
		}

		$coin = $this->request->getParam('coin');
		$this->loadModel('CharactersConditions');
		$data = $this->CharactersConditions->find()
					->enableHydration(false)
					->select(['player_id' => 'Characters.player_id'])
					->where(['CharactersConditions.condition_id' => $coin])
					->contain('Characters')
					->first();

		return isset($data['player_id']) ? $data['player_id'] : NULL;
	}
}
