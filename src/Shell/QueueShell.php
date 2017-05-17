<?php
namespace App\Shell;

use App\View\PdfView;
use Cake\Console\ConsoleIo;
use Cake\Console\Shell;

/**
 * Simple console wrapper around Psy\Shell.
 */
class QueueShell extends Shell
{

	public function initialize()
	{
		parent::initialize();
		$this->_io->level(ConsoleIo::QUIET);
		$this->loadModel('Lammies');
	}

	public function main()
	{
		$result = $this->Lammies->find('lastInQueue')->all();
		if($result->count() == 0)
			$this->quiet(0);
		else
			$this->quiet($result->first()->id);
	}

    public function single($id = 0)
    {
		$this->createPdf($id);
    }

    public function double($id = 0)
    {
		$this->createPdf($id, true);
    }

	private function createPdf($id, $double = false)
	{
		$query = $this->Lammies->find('queued');
		$query->where(["id <=" => $id]);
		$lammies = $query->all();
		if($lammies->count() == 0)
			return;

		$this->Lammies->setStatuses($lammies, 'Printing');

		$lammies = $lammies->map(function($value, $key) {
				return $value->lammy;
			})->toArray();
		$this->quiet((new PdfView())->createPdf($lammies, $double));
	}

	public function printed($id)
	{
		$lammies = $this->Lammies->find('printing')->all();
		$this->Lammies->setStatuses($lammies, 'Printed');
		$this->quiet($lammies->count());
	}

}
