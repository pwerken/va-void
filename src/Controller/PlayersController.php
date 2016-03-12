<?php
namespace App\Controller;

use App\Controller\AppController;

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

	protected function canDelete($entity)
	{
		$this->loadModel('Characters');
		$query = $this->Characters->find();
		$query->where(['player_id' => $entity->id]);
		return ($query->count() == 0);
	}

}
