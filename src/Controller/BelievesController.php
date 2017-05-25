<?php
namespace App\Controller;

class BelievesController
	extends AppController
{

	protected $searchFields = [ 'Believes.name' ];

	public function initialize()
	{
		parent::initialize();

		$this->mapMethod('add',    [ 'referee' ]);
		$this->mapMethod('delete', [ 'referee' ]);
		$this->mapMethod('edit',   [ 'referee' ]);
		$this->mapMethod('index',  [ 'player'  ]);
		$this->mapMethod('view',   [ 'player'  ]);
	}

	public function index()
	{
		$query = 'SELECT `believes`.`id`, `believes`.`name`'
				.' FROM `believes`'
				.' ORDER BY `believes`.`name` ASC';
		$this->doRawIndex($query, 'Belief', '/believes/');
	}

}
