<?php
declare(strict_types=1);

namespace App\Controller;

class EventsController
	extends AppController
{

	protected $searchFields = [ 'Events.name' ];

	public function initialize(): void
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

		$query = $this->Events->find()
					->select(['Events.id', 'Events.name'], true);
		$this->doRawIndex($query, 'Event', '/events/');
	}
}
