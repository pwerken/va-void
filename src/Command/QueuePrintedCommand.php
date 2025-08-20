<?php
declare(strict_types=1);

namespace App\Command;

use App\Command\Traits\CommandAuthorizationTrait;
use App\Model\Enum\LammyStatus;
use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;

class QueuePrintedCommand extends Command
{
    use CommandAuthorizationTrait;

    protected ?string $defaultTable = 'Lammies';

    public static function defaultName(): string
    {
        return 'queue printed';
    }

    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser->setDescription('Mark queued lammies as printed.');
        $parser->addArgument(
            'id',
            [ 'help' => 'ID of last queued lammy to include.'
                    , 'required' => true,
            ],
        );

        $parser->removeOption('quiet');
        $parser->removeOption('verbose');

        return $parser;
    }

    public function execute(Arguments $args, ConsoleIo $io): int
    {
        $id = (int)$args->getArgument('id');
        $lammies = $this->fetchTable()
                        ->find('printing')
                        ->where(['Lammies.id <=' => $id])
                        ->all();

        $this->fetchTable()->setStatuses($lammies, LammyStatus::Printed);

        $io->out((string)$lammies->count());

        return static::CODE_SUCCESS;
    }
}
