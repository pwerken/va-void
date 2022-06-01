<?php
declare(strict_types=1);

namespace App\Controller;

class BelievesController
	extends AppController
{

	protected $searchFields = [ 'Believes.name' ];

	public function initialize(): void
	{
		parent::initialize();

		$this->mapMethod('add',    [ 'referee'   ]);
		$this->mapMethod('delete', [ 'super'     ]);
		$this->mapMethod('edit',   [ 'infobalie' ]);
		$this->mapMethod('index',  [ 'player'    ]);
		$this->mapMethod('view',   [ 'player'    ]);
	}

	public function index()
	{
		if($this->setResponseModified())
			return $this->response;

		$query = $this->Believes->find()
					->select(['Believes.id', 'Believes.name'], true);
		$this->doRawIndex($query, 'Belief', '/believes/');
	}
}
