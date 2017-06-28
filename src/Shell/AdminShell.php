<?php
namespace App\Shell;

use App\Utility\CheckConfig;
use Cake\Console\Shell;

class AdminShell extends Shell
{
	public function checks()
	{
		$status = CheckConfig::installation();
		foreach($status as $msg => $ok) {
			if($ok) {
				$this->out($msg);
			} else {
				$this->err($msg);
			}
		}
	}

	public function getOptionParser()
	{
		$parser = parent::getOptionParser();
		$parser->setDescription(['Admin operations'])
			->addSubcommand('checks',
				[ 'help' => 'Run application configuration and setup checks.'
				])
			->removeOption('verbose');

		foreach($parser->subcommands() as $sub) {
			$sub = $sub->parser();
			if(!$sub) continue;

			$sub->removeOption('verbose');
		}

		return $parser;
	}
}
