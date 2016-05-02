<?php
namespace App\Controller;

use App\View\PdfView;
use Cake\Event\Event;
use Cake\Network\Exception\NotFoundException;

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
		$this->Crud->mapAction('pdfSingle',    $config);
		$this->Crud->mapAction('pdfDouble',    $config);
		$this->Crud->mapAction('jobItems',     $config);
		$this->Crud->mapAction('pdfJobSingle', $config);
		$this->Crud->mapAction('pdfJobDouble', $config);
	}

	public function jobItems($id) {
		$this->Crud->on('beforePaginate', function(Event $event) use ($id) {
			$event->subject()->query->where(['job' => $id]);
		});
		return $this->Crud->execute();
	}
	public function pdfJobSingle($id) {
		return $this->jobItems($id);
	}
	public function pdfJobDouble($id) {
		return $this->jobItems($id);
	}

	public function implementedEvents()
	{
		$events = parent::implementedEvents();
		$events['Crud.beforeRender'] = 'CrudBeforeRender';
		return $events;
	}

	public function CrudBeforeRender(Event $event)
	{
		if(strcmp($this->request->action, 'index') == 0)
			return PdfView::addLayoutInfo($event->subject->entities);

		if($event->subject->entities->count() == 0)
			throw new NotFoundException();

		if(strcmp(substr($this->request->action, 0, 3), 'pdf') !== 0)
			return;

		PdfView::addLayoutInfo($event->subject->entities);
		$this->viewBuilder()->className('Pdf');

		$this->set('double', false);
		if(strcmp(substr($this->request->action, -6), 'Double') === 0)
			$this->set('double', true);

		$this->set('page', -1);
		if(strcmp(substr($this->request->action, 0, 6), 'pdfJob') <> 0
		&& isset($this->request->params['pass'][0]))
			$this->set('page', $this->request->params['pass'][0]);
	}

}
