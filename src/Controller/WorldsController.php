<?php
namespace App\Controller;

class WorldsController
	extends AppController
{

	public function initialize()
	{
		parent::initialize();

		$contain = [ 'Characters' ];

		$this->mapMethod('add',    [ 'referee' ]);
		$this->mapMethod('delete', [ 'referee' ]);
		$this->mapMethod('edit',   [ 'referee' ]);
		$this->mapMethod('index',  [ 'player'  ]);
		$this->mapMethod('view',   [ 'player'  ], $contain);
	}

}
