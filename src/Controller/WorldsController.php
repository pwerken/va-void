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
		$query = 'SELECT `worlds`.`id`, `worlds`.`name`'
				.' FROM `worlds`'
				.' ORDER BY `worlds`.`name` ASC';
		$this->doRawIndex($query, 'World', '/worlds/');
	}

}
