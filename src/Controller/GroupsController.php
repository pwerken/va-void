<?php
namespace App\Controller;

class GroupsController
	extends AppController
{

	protected $searchFields = [ 'Groups.name' ];

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
		$query = 'SELECT `groups`.`id`, `groups`.`name`'
				.' FROM `groups`'
				.' ORDER BY `groups`.`name` ASC';
		$this->doRawIndex($query, 'Group', '/groups/');
	}

}
