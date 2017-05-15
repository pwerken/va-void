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
		$this->Crud->mapAction('queue', $config);

		$config['auth']       = [ 'super' ];
		$this->Crud->mapAction('printed',     $config);
		$this->Crud->mapAction('queueSingle', $config);
		$this->Crud->mapAction('queueDouble', $config);
		$this->Crud->mapAction('pdfSingle',   $config);
		$this->Crud->mapAction('pdfDouble',   $config);
	}

	public function queue()
	{
		$this->Crud->on('beforePaginate', function ($event) {
			$query = $event->subject()->query;
			$query->where(["Status =" => "Queued"]);
		});
		$this->Crud->execute();
	}

	public function queueSingle()
	{
		$this->Crud->on('beforePaginate', function ($event) {
			$query = $event->subject()->query;
			$query->where(["Status =" => "Queued"]);
			$query->limit(6);
		});
		$this->Crud->on('beforeRender', function ($event) {
			$count = 0;
			$id = -1;
			foreach ($event->subject()->entities as $lammy) {
				$count += $lammy->Lammy->sides();
				if($count > 12)
					break;
				$id = $lammy->id;
			}
			$event->subject()->entities = $id;
		});
		$this->Crud->execute();
	}

	public function queueDouble()
	{
		$this->Crud->on('beforePaginate', function ($event) {
			$query = $event->subject()->query;
			$query->where(["Status =" => "Queued"]);
			$query->limit(12);
		});
		$this->Crud->on('beforeRender', function ($event) {
			$count = 0;
			$id = -1;
			foreach ($event->subject()->entities as $lammy) {
				$count += $lammy->Lammy->cards();
				if($count > 12)
					break;
				$id = $lammy->id;
			}
			$event->subject()->entities = $id;
		});
		$this->Crud->execute();
	}

	public function pdfSingle()
	{
		$this->uptoId('Queued', key($this->request->data));
		$this->pdfOutput(false);
		$this->Crud->execute();
	}

	public function pdfDouble()
	{
		$this->uptoId('Queued', key($this->request->data));
		$this->pdfOutput(true);
		$this->Crud->execute();
	}

	public function printed()
	{
		$this->uptoId('Printing', key($this->request->data));
		$this->Crud->on('beforeRender', function ($event) {
			$this->setStatus($event->subject()->entities, 'Printed');
		});
		$this->Crud->execute();
	}

	private function uptoId($status, $id)
	{
		$this->Crud->on('beforePaginate', function ($event) use ($status, $id)
		{
			$query = $event->subject()->query;
			$query->where(["Status =" => $status, "id <=" => $id]);
		});
	}

	private function pdfOutput($double = false)
	{
		$this->Crud->on('beforeRender', function ($event) use ($double) {
			$this->setStatus($event->subject()->entities, 'Printing');

			PdfView::addLayoutInfo($event->subject()->entities);
			$this->viewBuilder()->className('Pdf');
			$this->set('double', $double);
		});
	}

	private function setStatus($lammies, $status)
	{
		$table = $this->loadModel();
		foreach($lammies as $lammy) {
			$lammy->status = $status;
			$table->save($lammy);
		}
	}

}
