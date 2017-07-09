<?php
namespace App\Controller;

class GroupsController
	extends AppController
{

	protected $searchFields = [ 'Groups.name' ];

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

		$query = $this->Groups->find()
					->select(['Groups.id', 'Groups.name'], true);
		$this->doRawIndex($query, 'Group', '/groups/');
	}

}
