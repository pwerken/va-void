<?php
namespace App\Controller;

class SkillsController
	extends AppController
{

	public function initialize()
	{
		parent::initialize();

		$contain = [ 'Manatypes' ];

		$this->mapMethod('add',    [ 'super'  ]);
		$this->mapMethod('delete', [ 'super'  ]);
		$this->mapMethod('edit',   [ 'super'  ]);
		$this->mapMethod('index',  [ 'player' ], $contain);
		$this->mapMethod('view',   [ 'player' ], $contain);
	}

	protected function canDelete($entity)
	{
		$this->loadModel('CharactersSkills');
		$query = $this->CharactersSkills->find();
		$query->where(['skill_id' => $entity->id]);
		return ($query->count() == 0);
	}

}
