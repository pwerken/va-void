<?php
declare(strict_types=1);

namespace App\Controller;

use App\View\PdfView;
use Cake\Event\Event;

class LammiesController
	extends AppController
{

	protected $searchFields =
		[ 'Lammies.status'
		];

	public function initialize(): void
	{
		parent::initialize();

		$this->mapMethod('add',    [ 'super'     ]);
		$this->mapMethod('edit',   [ 'super'     ]);
		$this->mapMethod('delete', [ 'super'     ]);
		$this->mapMethod('index',  [ 'read-only' ]);
		$this->mapMethod('view',   [ 'read-only' ]);

		$config = [];
		$config['auth']       = [ 'referee' ];
		$config['className']  = 'Crud.Index';
		$config['findMethod'] = 'lastInQueue';
		$this->Crud->mapAction('queue', $config);

		$config['auth']       = [ 'infobalie' ];
		$config['findMethod'] = 'printing';
		$this->Crud->mapAction('printed', $config);

		$config['findMethod'] = 'queued';
		$this->Crud->mapAction('pdfSingle', $config);
		$this->Crud->mapAction('pdfDouble', $config);
	}

	public function index()
	{
		if($this->setResponseModified())
			return $this->response;

		$query = $this->Lammies->find()
					->select([], true)
					->select('Lammies.id')
					->select('Lammies.status')
					->select('Lammies.entity')
					->select('Lammies.key1')
					->select('Lammies.key2')
					->select('Lammies.modified');
		$content = [];
		foreach($this->doRawQuery($query) as $row) {
			$content[] =
				[ 'class' => 'Lammy'
				, 'url' => '/lammies/'.$row[0]
				, 'status' => $row[1]
				, 'entity' => $row[2]
				, 'key1' => (int)$row[3]
				, 'key2' => (int)$row[4]
				, 'modified' => $row[5]
				];
		}
		$this->set('_serialize',
			[ 'class' => 'List'
			, 'url' => rtrim($this->request->getPath(), '/')
			, 'list' => $content
			]);
	}

	public function queue()
	{
		$this->Crud->on('beforeRender', function ($event) {
			$subject = $event->getSubject();
			if($subject->entities->count() == 0) {
				$id = 0;
			} else {
				$id = $subject->entities->first()->id;
			}
			$subject->entities = $id;
		});
		$this->Crud->execute();
	}

	public function pdfSingle()
	{
		$this->uptoId(key($this->request->getData()));
		$this->pdfOutput(false);
		$this->Crud->execute();
	}

	public function pdfDouble()
	{
		$this->uptoId(key($this->request->getData()));
		$this->pdfOutput(true);
		$this->Crud->execute();
	}

	public function printed()
	{
		$this->uptoId(key($this->request->data));

		$this->Crud->on('beforeRender', function ($event) {
			$subject = $event->getSubject();
			$table = $this->loadModel();
			$table->setStatuses($subject->entities, 'Printed');
			$subject->entities = $subject->entities->count();
		});

		$this->Crud->execute();
	}

	private function uptoId($id)
	{
		$this->Crud->on('beforePaginate', function ($event) use ($id) {
			$event->getSubject()->query->where(["id <=" => $id]);
		});
	}

	private function pdfOutput($double = false)
	{
		$this->Crud->on('beforeRender', function ($event) use ($double) {
			$table = $this->loadModel();
			$table->setStatuses($event->getSubject()->entities, 'Printing');
			$this->viewBuilder()->setClassName('Pdf');
			$this->set('double', $double);
		});
	}
}
