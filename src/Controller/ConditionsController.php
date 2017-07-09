<?php
namespace App\Controller;

class ConditionsController
	extends AppController
{

	protected $searchFields =
		[ 'Conditions.name'
		, 'Conditions.player_text'
		, 'Conditions.cs_text'
		];

	public function initialize()
	{
		parent::initialize();

		$this->mapMethod('add',    [ 'infobalie'       ]);
		$this->mapMethod('delete', [ 'super'           ]);
		$this->mapMethod('edit',   [ 'infobalie'       ]);
		$this->mapMethod('index',  [ 'players'         ]);
		$this->mapMethod('view',   [ 'referee', 'user' ], true);

		$this->Crud->mapAction('queue',
			[ 'className' => 'Crud.View'
			, 'auth' => [ 'infobalie' ]
			, 'findMethod' => 'withContain'
			]);
	}

	public function index()
	{
		if($this->setResponseModified())
			return $this->response;

		$query = $this->Conditions->find()
					->select(['Conditions.id', 'Conditions.name'], true);

		if(!$this->hasAuth('referee')) {
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
		if(!$this->hasAuth('referee')) {
			$this->Crud->on('beforeFind', function ($event) {
				$cond = ['Characters.player_id' => $this->Auth->user('id')];
				$event->subject()->query->contain(
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
		$this->queueLammy();
	}

	protected function wantAuthUser()
	{
		$plin = parent::wantAuthUser();
		if($plin !== false) {
			return $plin;
		}

		$coin = $this->request->param('coin');
		$this->loadModel('CharactersConditions');
		$data = $this->CharactersConditions->find()
					->hydrate(false)
					->select(['player_id' => 'Characters.player_id'])
					->where(['CharactersConditions.condition_id' => $coin])
					->contain('Characters')
					->first();

		return isset($data['player_id']) ? $data['player_id'] : NULL;
	}
}
