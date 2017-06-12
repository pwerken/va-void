<?php
namespace App\Shell;

use App\View\PdfView;
use Cake\Console\ConsoleIo;
use Cake\Console\Shell;
use Cake\Filesystem\File;

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
		$query = $this->Lammies->find('printing');
		$query->where(["Lammies.id <=" => $id]);
		$lammies = $query->all();

		$this->Lammies->setStatuses($lammies, 'Printed');
		$this->quiet($lammies->count());
	}

	public function getOptionParser()
	{
		$parser = parent::getOptionParser();
		$parser->setDescription(
				[ 'Handle lammy printing queue and pdf generation.', ''
				, 'Returns the <id> of the last unprinted lammy in the queue'
				. ' when called with out any parameters.'
				])
			->addSubcommand('single',
				[ 'help' => 'Create a PDF for single-sided printing.'
				, 'parser' =>
					[ 'arguments' =>
						[ 'id' =>
							[ 'help' => '<id> of the last lammy to include.'
							, 'required' => true
							]
						, 'filename' =>
							[ 'help' => 'It can be an absolute path.'
							, 'required' => false
				]	]	]	])
			->addSubcommand('double',
				[ 'help' => 'Create a PDF for double-sided printing.'
				, 'parser' =>
					[ 'arguments' =>
						[ 'id' =>
							[ 'help' => '<id> of the last lammy to include.'
							, 'required' => true
							]
						, 'filename' =>
							[ 'help' => 'It can be an absolute path.'
							, 'required' => false
				]	]	]	])
			->addSubcommand('printed',
				[ 'help' => 'Mark queued lammies as printed.'
				, 'parser' =>
					[ 'arguments' =>
						[ 'id' =>
							[ 'help' => 'Mark lammies as printed'
										. ' up to and including <id>.'
							, 'required' => true
				]	]	]	])
			->removeOption('quiet')
			->removeOption('verbose');

		foreach($parser->subcommands() as $sub) {
			$sub = $sub->parser();
			if(!$sub) continue;

			$sub->removeOption('quiet');
			$sub->removeOption('verbose');
		}

		return $parser;
	}
}
