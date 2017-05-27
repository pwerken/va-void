<?php
namespace App\Controller;

class FactionsController
	extends AppController
{

	protected $searchFields = [ 'Factions.name' ];

	public function initialize()
	{
		parent::initialize();

		$this->mapMethod('add',    [ 'super'  ]);
		$this->mapMethod('delete', [ 'super'  ]);
		$this->mapMethod('edit',   [ 'super'  ]);
		$this->mapMethod('index',  [ 'player' ]);
		$this->mapMethod('view',   [ 'player' ], true);
	}

	public function index()
	{
		if($this->setResponseModified())
			return $this->response;

		$query = $this->Factions->find()
					->select(['Factions.id', 'Factions.name'], true);
		$this->doRawIndex($query, 'Faction', '/factions/');
	}

}
