<?php
namespace App\Controller;

class ItemsController
	extends AppController
{

	public function initialize()
	{
		parent::initialize();

		$char    = [ 'Characters' ];
		$contain = [ 'Characters', 'Attributes' ];

		$this->mapMethod('add',              [ 'referee'         ], $char);
		$this->mapMethod('delete',           [ 'super'           ], $char);
		$this->mapMethod('edit',             [ 'referee'         ], $char);
		$this->mapMethod('index',            [ 'referee'         ], $char);
		$this->mapMethod('view',             [ 'referee', 'user' ], $contain);

		$this->mapMethod('charactersIndex',  [ 'referee', 'user' ], $contain);
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
