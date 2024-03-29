<?php
declare(strict_types=1);

namespace App\Command;

use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;

use App\Command\Traits\PrintLammies;

class QueueSingleCommand
    extends Command
{
    use PrintLammies;

    protected $defaultTable = 'Lammies';

    public static function defaultName(): string
    {
        return 'queue single';
    }

    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parset->setDescription('Create a PDF for single-sided printing.');
        $parser->addArgument('id',
                    [ 'help' => 'ID of the last queued lammy to include.'
                    , 'required' => true
                    ]);

        $parser->removeOption('quiet');
        $parser->removeOption('verbose');
        return $parser;
    }

    public function execute(Arguments $args, ConsoleIo $io): int
    {
        $pdf = $this->createPdf((int)$args->getArgument('id'), false);

        if(isset($pdf)) {
            $io->out($pdf);
        }

        return static::CODE_SUCCESS;
    }
}
