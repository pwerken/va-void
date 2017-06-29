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

		$this->mapMethod('add',    [ 'referee'         ]);
		$this->mapMethod('delete', [ 'super'           ]);
		$this->mapMethod('edit',   [ 'referee'         ]);
		$this->mapMethod('index',  [ 'players'         ]);
		$this->mapmethod('view',   [ 'referee', 'user' ], true);

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

		$query = $this->Powers->find()
					->select(['Powers.id', 'Powers.name'], true);

		if(!AuthState::hasRole('referee')) {
			$plin = $this->Auth->user('id');
			$query->where(["Characters.player_id = $plin"])
					->leftJoin(['CharactersPowers' => 'characters_powers'],
						    ['CharactersPowers.power_id = Powers.id'])
					->leftJoin(['Characters' => 'characters'],
						    ['Characters.id = CharactersPowers.character_id']);
		}

		$this->doRawIndex($query, 'Power', '/powers/', 'poin');
	}

	public function queue($poin)
	{
		$this->queueLammy();
	}

	protected function wantAuthUser()
	{
		$poin = $this->request->param('poin');
		$this->loadModel('CharactersPowers');
		$data = $this->CharactersPowers->find()
					->hydrate(false)
					->select(['player_id' => 'Characters.player_id'])
					->where(['CharactersPowers.power_id' => $poin])
					->contain('Characters')
					->first();

		if(!is_null($data)) {
			return $data['player_id'];
		}

		return parent::wantAuthUser();
	}

}
