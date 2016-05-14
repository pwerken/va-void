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
		$this->mapMethod('index',  [ 'referee'         ]);
		$this->mapMethod('view',   [ 'referee', 'user' ], true);
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
