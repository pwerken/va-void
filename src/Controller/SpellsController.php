<?php
namespace App\Controller;

class SpellsController
	extends AppController
{

	public function initialize()
	{
		parent::initialize();

		$this->mapMethod('add',    [ 'super'  ]);
		$this->mapMethod('delete', [ 'super'  ]);
		$this->mapMethod('edit',   [ 'super'  ]);
		$this->mapMethod('index',  [ 'player' ]);
		$this->mapMethod('view',   [ 'player' ]);
	}

	protected function canDelete($entity)
	{
		$this->loadModel('CharactersSpells');
		$query = $this->CharactersSpells->find();
		$query->where(['spell_id' => $entity->id]);
		return ($query->count() == 0);
	}

}
