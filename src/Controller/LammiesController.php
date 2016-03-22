<?php
namespace App\Controller;

use Cake\Event\Event;

class LammiesController
	extends AppController
{

	public function initialize()
	{
		parent::initialize();

		$this->mapMethod('add',    [ 'referee'   ]);
		$this->mapMethod('edit',   [ 'infobalie' ]);
		$this->mapMethod('delete', [ 'super'     ]);
		$this->mapMethod('index',  [ 'referee'   ]);
		$this->mapMethod('view',   [ 'referee'   ]);

		$this->Crud->mapAction('print',
			[ 'className' => 'Crud.View'
			, 'auth' => [ 'referee' ]
			]);
	}

	public function implementedEvents()
	{
		$events = parent::implementedEvents();
		$events['Crud.beforeRender'] = 'CrudBeforeRender';
		return $events;
	}

	public function CrudBeforeRender(Event $event)
	{
		if(strcmp($this->request->action, 'print') !== 0)
			return;

		$this->viewBuilder()->className('Pdf');
	}

}
