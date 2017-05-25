<?php
namespace App\Controller;

class CharactersPowersController
	extends AppController
{

	public function initialize()
	{
		parent::initialize();

		$this->mapMethod('charactersAdd',    [ 'referee'         ]);
		$this->mapMethod('charactersDelete', [ 'referee'         ]);
		$this->mapMethod('charactersEdit',   [ 'referee'         ], true);
		$this->mapMethod('charactersIndex',  [ 'referee', 'user' ], true);
		$this->mapMethod('charactersView',   [ 'referee'         ], true);

		$this->mapMethod('powersIndex',      [ 'referee'         ], true);

		$this->Crud->mapAction('charactersQueue',
			[ 'className' => 'Crud.View'
			, 'auth' => [ 'referee' ]
			, 'findMethod' => 'withContain'
			]);
	}

	public function charactersQueue($plin, $chin, $coin)
	{
		$this->Crud->on('beforeRender', function ($event) {
			$table = $this->loadModel('lammies');
			$item = $event->subject()->entity;
			$table->save($table->newEntity()->set('target', $item));
			$event->subject()->entity = 1;
		});

		$this->Crud->execute();
	}

}
