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
		$query = $this->Groups->find()
					->select(['Groups.id', 'Groups.name'], true);
		$this->doRawIndex($query, 'Group', '/groups/');
	}

}
