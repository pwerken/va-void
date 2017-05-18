<?php
namespace App\Controller;

use App\View\PdfView;
use Cake\Event\Event;

class LammiesController
	extends AppController
{

	protected $searchFields =
		[ 'Lammies.status'
		];

	public function initialize()
	{
		parent::initialize();

		$this->mapMethod('add',    [ 'super'   ]);
		$this->mapMethod('edit',   [ 'super'   ]);
		$this->mapMethod('delete', [ 'super'   ]);
		$this->mapMethod('index',  [ 'referee' ]);
		$this->mapMethod('view',   [ 'referee' ]);

		$config = [];
		$config['auth']       = [ 'referee' ];
		$config['className']  = 'Crud.Index';
		$config['findMethod'] = 'lastInQueue';
		$this->Crud->mapAction('queue', $config);

		$config['auth']       = [ 'super' ];
		$config['findMethod'] = 'printing';
		$this->Crud->mapAction('printed', $config);

		$config['findMethod'] = 'queued';
		$this->Crud->mapAction('pdfSingle', $config);
		$this->Crud->mapAction('pdfDouble', $config);
	}

	public function queue()
	{
		$this->Crud->on('beforeRender', function ($event) {
			if($event->subject()->entities->count() == 0) {
				$id = 0;
			} else {
				$id = $event->subject()->entities->first()->id;
			}
			$event->subject()->entities = $id;
		});
		$this->Crud->execute();
	}

	public function pdfSingle()
	{
		$this->uptoId(key($this->request->data));
		$this->pdfOutput(false);
		$this->Crud->execute();
	}

	public function pdfDouble()
	{
		$this->uptoId(key($this->request->data));
		$this->pdfOutput(true);
		$this->Crud->execute();
	}

	public function printed()
	{
		$this->uptoId(key($this->request->data));

		$this->Crud->on('beforeRender', function ($event) {
			$table = $this->loadModel();
			$table->setStatuses($event->subject()->entities, 'Printed');

			$event->subject()->entities = $event->subject()->entities->count();
		});

		$this->Crud->execute();
	}

	private function uptoId($id)
	{
		$this->Crud->on('beforePaginate', function ($event) use ($id) {
			$event->subject()->query->where(["id <=" => $id]);
		});
	}

	private function pdfOutput($double = false)
	{
		$this->Crud->on('beforeRender', function ($event) use ($double) {
			$table = $this->loadModel();
			$table->setStatuses($event->subject()->entities, 'Printing');

			PdfView::addLayoutInfo($event->subject()->entities);
			$this->viewBuilder()->className('Pdf');
			$this->set('double', $double);
		});
	}

}
