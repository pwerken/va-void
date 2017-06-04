<?php
namespace App\Shell;

use App\View\PdfView;
use Cake\Console\ConsoleIo;
use Cake\Console\Shell;
use Cake\Filesystem\File;

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

    public function single($id = 0, $filename = NULL)
    {
		$this->createPdf($id, $filename);
    }

    public function double($id = 0, $filename = NULL)
    {
		$this->createPdf($id, $filename, true);
    }

	private function createPdf($id, $filename, $double = false)
	{
		$query = $this->Lammies->find('queued');
		$query->where(["Lammies.id <=" => $id]);
		$lammies = $query->all();
		if($lammies->count() == 0)
			return;

		$this->Lammies->setStatuses($lammies, 'Printing');

		$lammies = $lammies->map(function($value, $key) {
				return $value->lammy;
			})->toArray();

		if(empty($filename)) {
			$this->quiet((new PdfView())->createPdf($lammies, $double));
			return;
		}

		$file = new File($filename, true);
		$file->write((new PdfView())->createPdf($lammies, $double));
		$file->close();
	}

	public function printed($id)
	{
		$lammies = $this->Lammies->find('printing')->all();
		$this->Lammies->setStatuses($lammies, 'Printed');
		$this->quiet($lammies->count());
	}

}
