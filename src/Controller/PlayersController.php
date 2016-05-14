<?php
namespace App\Controller;

class PlayersController
	extends AppController
{

	protected $searchFields =
		[ 'Players.first_name'
		, 'Players.insertion'
		, 'Players.last_name'
		];

	public function initialize()
	{
		parent::initialize();

		$this->mapMethod('add',    [ 'infobalie'         ]);
		$this->mapMethod('edit',   [ 'infobalie', 'user' ]);
		$this->mapMethod('delete', [ 'super'             ]);
		$this->mapMethod('index',  [ 'referee'           ]);
		$this->mapMethod('view',   [ 'referee',   'user' ], true);
	}

}
