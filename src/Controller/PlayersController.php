<?php
namespace App\Controller;

class PlayersController
	extends AppController
{

	public function initialize()
	{
		parent::initialize();

		$contain = [ 'Characters' ];

		$this->mapMethod('add',    [ 'infobalie'         ]);
		$this->mapMethod('edit',   [ 'infobalie', 'user' ]);
		$this->mapMethod('delete', [ 'super'             ]);
		$this->mapMethod('index',  [ 'referee'           ]);
		$this->mapMethod('view',   [ 'referee',   'user' ], $contain);
	}

}
