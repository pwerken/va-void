<?php
namespace App\Controller;

class FactionsController
	extends AppController
{

	public function initialize()
	{
		parent::initialize();

		$contain = [ 'Characters' ];

		$this->mapMethod('add',    [ 'super'  ]);
		$this->mapMethod('delete', [ 'super'  ]);
		$this->mapMethod('edit',   [ 'super'  ]);
		$this->mapMethod('index',  [ 'player' ]);
		$this->mapMethod('view',   [ 'player'  ], $contain);
	}

	protected function canDelete($entity)
	{
		$this->loadModel('Players');
		$query = $this->Players->find();
		$query->where(['faction_id' => $entity->id]);
		return ($query->count() == 0);
	}

}
