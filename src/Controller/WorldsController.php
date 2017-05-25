<?php
namespace App\Controller;

class WorldsController
	extends AppController
{

	protected $searchFields = [ 'Worlds.name' ];

	public function initialize()
	{
		parent::initialize();

		$this->mapMethod('add',    [ 'referee' ]);
		$this->mapMethod('delete', [ 'referee' ]);
		$this->mapMethod('edit',   [ 'referee' ]);
		$this->mapMethod('index',  [ 'player'  ]);
		$this->mapMethod('view',   [ 'player'  ], true);
	}

	public function index()
	{
		$query = $this->Worlds->find()
					->select(['Worlds.id', 'Worlds.name'], true);
		$this->doRawIndex($query, 'World', '/worlds/');
	}

}
