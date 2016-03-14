<?php
namespace App\Controller;

class BelievesController
	extends AppController
{

	public function initialize()
	{
		parent::initialize();

		$this->mapMethod('add',    [ 'referee' ]);
		$this->mapMethod('delete', [ 'referee' ]);
		$this->mapMethod('edit',   [ 'referee' ]);
		$this->mapMethod('index',  [ 'player'  ]);
		$this->mapMethod('view',   [ 'player'  ]);
	}

	protected function canDelete($entity)
	{
		$this->loadModel('Characters');
		$query = $this->Characters->find();
		$query->where(['belief_id' => $entity->id]);
		return ($query->count() == 0);
	}

}
