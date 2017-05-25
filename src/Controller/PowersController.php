<?php
namespace App\Controller;

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
		$this->mapMethod('index',  [ 'referee'         ]);
		$this->mapmethod('view',   [ 'referee', 'user' ], true);
	}

	public function index()
	{
		$query = 'SELECT `powers`.`id`, `powers`.`name`'
				.' FROM `powers`'
				.' ORDER BY `powers`.`id`';
		$this->doRawIndex($query, 'Power', '/powers/', 'poin');
	}

	protected function hasAuthUser($id = null)
	{
		$poin = $this->request->param('poin');
		$this->loadModel('CharactersPowers');
		$data = $this->CharactersPowers->find()
					->hydrate(false)
					->select(['player_id' => 'Characters.player_id'])
					->where(['CharactersPowers.power_id' => $poin])
					->contain('Characters')
					->first();
		return parent::hasAuthUser(@$data['player_id']);
	}

}
