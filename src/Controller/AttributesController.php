<?php
namespace App\Controller;

use App\Controller\AppController;

class AttributesController
	extends AppController
{

	public function initialize()
	{
		parent::initialize();

		$this->mapMethod('add',    [ 'super'   ]);
		$this->mapMethod('delete', [ 'super'   ]);
		$this->mapMethod('edit',   [ 'super'   ]);
		$this->mapMethod('index',  [ 'referee' ]);
		$this->mapMethod('view',   [ 'referee' ]);
	}

	protected function canDelete($entity)
	{
		$this->loadModel('AttributesItems');
		$query = $this->AttributesItems->find();
		$query->where(['character_id' => $entity->id]);
		return ($query->count() == 0)
	}

}
