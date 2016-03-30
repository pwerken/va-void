<?php
namespace App\Controller;

use App\View\PdfView;
use Cake\Event\Event;

class LammiesController
	extends AppController
{

	public function initialize()
	{
		parent::initialize();

		$this->mapMethod('add',    [ 'super'     ]);
		$this->mapMethod('edit',   [ 'super'     ]);
		$this->mapMethod('delete', [ 'super'     ]);
		$this->mapMethod('index',  [ 'referee'   ]);
		$this->mapMethod('view',   [ 'referee'   ]);

		$config = [];
		$config['className']  = 'Crud.Index';
		$config['auth']       = [ 'referee' ];
		$this->Crud->mapAction('pdfSingle', $config);
		$this->Crud->mapAction('pdfDouble', $config);
	}

	public function implementedEvents()
	{
		$events = parent::implementedEvents();
		$events['Crud.beforeRender'] = 'CrudBeforeRender';
		return $events;
	}

	public function CrudBeforeRender(Event $event)
	{
		if(strcmp(substr($this->request->action, 0, 3), 'pdf') !== 0) {
			if(strcmp($this->request->action, 'index') == 0)
				PdfView::addLayoutInfo($event->subject->entities);
			return;
		}

		$this->viewBuilder()->className('Pdf');
		PdfView::addLayoutInfo($event->subject->entities);

		$this->set('double', false);
		if(strcmp(substr($this->request->action, 3, 6), 'Double') === 0)
			$this->set('double', true);

		$this->set('page', -1);
		if(isset($this->request->params['pass'][0]))
			$this->set('page', $this->request->params['pass'][0]);

	}

}
