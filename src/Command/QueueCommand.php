<?php
declare(strict_types=1);

namespace App\Command;

use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;

class QueueCommand extends Command
{
    protected ?string $defaultTable = 'Lammies';

    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser->setDescription('ID of the last unprinted lammy in the queue,'
                            . 'or 0 if everything is printed.');
        $parser->removeOption('quiet');
        $parser->removeOption('verbose');

        return $parser;
    }

    public function execute(Arguments $args, ConsoleIo $io): int
    {
        $result = $this->fetchTable()->find('lastInQueue')->all();
        if ($result->count() == 0) {
            $io->out('0');
        } else {
            $io->out((string)$result->first()->id);
        }

        return static::CODE_SUCCESS;
    }
}
