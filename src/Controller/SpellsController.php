<?php
declare(strict_types=1);

namespace App\Controller;

class SpellsController
	extends AppController
{

	protected $searchFields = [ 'Spells.name', 'Spells.short' ];

	public function initialize(): void
	{
		parent::initialize();

		$this->mapMethod('add',    [ 'super'  ]);
		$this->mapMethod('delete', [ 'super'  ]);
		$this->mapMethod('edit',   [ 'super'  ]);
		$this->mapMethod('index',  [ 'player' ]);
		$this->mapMethod('view',   [ 'player' ]);
	}
}
