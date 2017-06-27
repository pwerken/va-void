<?php
namespace App\Controller;

use App\AuthState;

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

		$this->mapMethod('add',    [ 'referee'         ]);
		$this->mapMethod('delete', [ 'super'           ]);
		$this->mapMethod('edit',   [ 'referee'         ]);
		$this->mapMethod('index',  [ 'players'         ]);
		$this->mapMethod('view',   [ 'referee', 'user' ], true);

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

		if(!AuthState::hasRole('referee')) {
			$plin = $this->Auth->user('id');
			$query->where(["Characters.player_id = $plin"])
					->leftJoin(['CharactersConditions' => 'characters_conditions'],
						    ['CharactersConditions.condition_id = Conditions.id'])
					->leftJoin(['Characters' => 'characters'],
						    ['Characters.id = CharactersConditions.character_id']);
		}

		$this->doRawIndex($query, 'Condition', '/conditions/', 'coin');
	}

	public function queue($coin)
	{
		$this->queueLammy();
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
