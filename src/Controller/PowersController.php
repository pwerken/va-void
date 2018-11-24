<?php
namespace App\Controller;

use App\Utility\AuthState;

class PowersController
	extends AppController
{

	protected $searchFields =
		[ 'Powers.name'
		, 'Powers.player_text'
		, 'Powers.cs_text'
		];

	public function initialize()
	{
		parent::initialize();

		$this->mapMethod('add',    [ 'referee'           ]);
		$this->mapMethod('delete', [ 'super'             ]);
		$this->mapMethod('edit',   [ 'referee'           ]);
		$this->mapMethod('index',  [ 'players'           ]);
		$this->mapmethod('view',   [ 'read-only', 'user' ], true);

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

		$query = $this->Powers->find()
					->select(['Powers.id', 'Powers.name'], true);

		if(!AuthState::hasRole('read-only')) {
			$plin = $this->Auth->user('id');
			$query->where(["Characters.player_id = $plin"])
					->leftJoin(['CharactersPowers' => 'characters_powers'],
						    ['CharactersPowers.power_id = Powers.id'])
					->leftJoin(['Characters' => 'characters'],
						    ['Characters.id = CharactersPowers.character_id']);
		}

		$this->doRawIndex($query, 'Power', '/powers/', 'poin');
	}

	public function view($poin)
	{
		if(!AuthState::hasRole('read-only')) {
			$this->Crud->on('beforeFind', function ($event) {
				$cond = ['Characters.player_id' => $this->Auth->user('id')];
				$event->getSubject()->query->contain(
					['CharactersPowers' => function ($query) use ($cond)
						{
							return $query->where($cond);
						}
					]);
			});
		}

		$this->Crud->execute();
	}

	public function queue($poin)
	{
		$this->queueLammy();
	}

	protected function wantAuthUser()
	{
		$plin = parent::wantAuthUser();
		if($plin !== false) {
			return $plin;
		}

		$poin = $this->request->getParam('poin');
		$this->loadModel('CharactersPowers');
		$data = $this->CharactersPowers->find()
					->enableHydration(false)
					->select(['player_id' => 'Characters.player_id'])
					->where(['CharactersPowers.power_id' => $poin])
					->contain('Characters')
					->first();

		return isset($data['player_id']) ? $data['player_id'] : NULL;
	}
}
