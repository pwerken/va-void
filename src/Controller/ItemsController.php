<?php
namespace App\Controller;

class ItemsController
	extends AppController
{

	protected $searchFields =
		[ 'Items.name'
		, 'Items.description'
		, 'Items.player_text'
		, 'Items.cs_text'
		];

	public function initialize()
	{
		parent::initialize();

		$this->mapMethod('add',              [ 'referee'         ]);
		$this->mapMethod('delete',           [ 'super'           ]);
		$this->mapMethod('edit',             [ 'referee'         ]);
		$this->mapMethod('index',            [ 'referee'         ]);
		$this->mapMethod('view',             [ 'referee', 'user' ], true);

		$this->mapMethod('charactersIndex',  [ 'referee', 'user' ], true);
	}

	public function edit($itin)
	{
		if(isset($this->request->data['plin'])
		|| isset($this->request->data['chin']))
		{
			$plin = $this->request->data('plin');
			$chin = $this->request->data('chin');

			$this->loadModel('Characters');
			$char = $this->Characters->findByPlayerIdAndChin($plin, $chin)->first();
			$this->request->data('character_id', $char ? $char->id : -1);
		}

		$this->request->data('plin', null);
		$this->request->data('chin', null);

		return $this->Crud->execute();
	}

	protected function hasAuthUser($id = null)
	{
		$itin = $this->request->param('itin');
		$data = $this->Items->find()
					->hydrate(false)
					->select(['player_id' => 'Characters.player_id'])
					->where(['Items.id' => $itin])
					->contain('Characters')
					->first();
		return parent::hasAuthUser(@$data['player_id']);
	}

}
