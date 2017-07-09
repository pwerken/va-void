<?php
namespace App\Controller;

class WorldsController
	extends AppController
{

	protected $searchFields = [ 'Worlds.name' ];

	public function initialize()
	{
		parent::initialize();

		$this->mapMethod('add',    [ 'infobalie' ]);
		$this->mapMethod('delete', [ 'infobalie' ]);
		$this->mapMethod('edit',   [ 'infobalie' ]);
		$this->mapMethod('index',  [ 'player'    ]);
		$this->mapMethod('view',   [ 'player'    ], true);
	}

	public function index()
	{
		if($this->setResponseModified())
			return $this->response;

		$query = $this->Worlds->find()
					->select(['Worlds.id', 'Worlds.name'], true);
		$this->doRawIndex($query, 'World', '/worlds/');
	}

}
