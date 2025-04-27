<?php
declare(strict_types=1);

namespace App\Command;

use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\Filesystem\File;

use App\View\PdfView;

class QueuePrintedCommand
    extends Command
{
    protected ?string $defaultTable = 'Lammies';

    public static function defaultName(): string
    {
        return 'queue printed';
    }

    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser->setDescription('Mark queued lammies as printed.');
        $parser->addArgument('id',
                    [ 'help' => 'ID of last queued lammy to include.'
                    , 'required' => true
                    ]);

        $parser->removeOption('quiet');
        $parser->removeOption('verbose');
        return $parser;
    }

    public function execute(Arguments $args, ConsoleIo $io): int
    {
        $id = $args->getArgument('id');
        $lammies = $this->fetchTable()
                        ->find('Printing')
                        ->where(['Lammies.id <=' => $id])
                        ->all();

        $this->Lammies->setStatuses($lammies, 'Printed');

        $io->out($lammies->count());

        return static::CODE_SUCCESS;
    }
}
