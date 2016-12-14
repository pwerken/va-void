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
		$this->mapMethod('index',            [ 'referee'         ], true);
		$this->mapMethod('view',             [ 'referee', 'user' ], true);

		$this->mapMethod('charactersIndex',  [ 'referee', 'user' ], true);
	}

	public function edit($itin)
	{
		if(array_key_exists('plin', $this->request->data)
		|| array_key_exists('chin', $this->request->data))
		{
			$plin = $this->request->data('plin');
			$chin = $this->request->data('chin');

			if($plin || $chin) {
				$this->loadModel('Characters');
				$char = $this->Characters->findByPlayerIdAndChin($plin, $chin)->first();
				$this->request->data('character_id', $char ? $char->id : -1);
			} else {
				$this->request->data('character_id', null);
			}
		}
		unset($this->request->data['plin']);
		unset($this->request->data['chin']);

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
