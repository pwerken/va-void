<?php
namespace App\Controller;

class SkillsController
	extends AppController
{

	protected $searchFields = [ 'Skills.name' ];

	public function initialize()
	{
		parent::initialize();

		$this->mapMethod('add',    [ 'super'  ]);
		$this->mapMethod('delete', [ 'super'  ]);
		$this->mapMethod('edit',   [ 'super'  ]);
		$this->mapMethod('index',  [ 'player' ], true);
		$this->mapMethod('view',   [ 'player' ], true);
	}

}
