<?php
declare(strict_types=1);

namespace App\Command;

use App\Command\Traits\CommandAuthorizationTrait;
use App\Command\Traits\PrintLammiesTrait;
use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;

class QueueSingleCommand extends Command
{
    use CommandAuthorizationTrait;
    use PrintLammiesTrait;

    protected ?string $defaultTable = 'Lammies';

    public static function defaultName(): string
    {
        return 'queue single';
    }

    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser->setDescription('Create a PDF for single-sided printing.');
        $parser->addArgument(
            'id',
            [ 'help' => 'ID of the last queued lammy to include.'
                    , 'required' => true,
            ],
        );

        $parser->removeOption('quiet');
        $parser->removeOption('verbose');

        return $parser;
    }

    public function execute(Arguments $args, ConsoleIo $io): int
    {
        $pdf = $this->createPdf((int)$args->getArgument('id'), false);

        if (isset($pdf)) {
            $io->out($pdf);
        }

        return static::CODE_SUCCESS;
    }
}
