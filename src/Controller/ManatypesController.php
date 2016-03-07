<?php
namespace App\Controller;

use App\Controller\AppController;

class ManatypesController
	extends AppController
{

	public function initialize()
	{
		parent::initialize();

		$this->mapMethod('add',    [ 'super'  ]);
		$this->mapMethod('delete', [ 'super'  ]);
		$this->mapMethod('edit',   [ 'super'  ]);
		$this->mapMethod('index',  [ 'player' ]);
		$this->mapMethod('view',   [ 'player' ], [ 'Skills' ]);
	}

	protected function canDelete($entity)
	{
		$this->loadModel('Skills');
		$query = $this->Skills->find();
		$query->where(['manatype_id' => $entity->id]);
		return ($query->count() == 0);
	}

}
