<?php
declare(strict_types=1);

namespace App\Command;

use App\Command\Traits\BackupTrait;
use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;

class BackupCommand extends Command
{
    use BackupTrait;

    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser->setDescription('List database backups.');
        $parser->removeOption('quiet');
        $parser->removeOption('verbose');

        return $parser;
    }

    public function execute(Arguments $args, ConsoleIo $io): int
    {
        $backups = $this->getBackupFiles();
        $headers = [ 'Filename', 'Size', 'Datetime' ];

        $io->helper('Table')->output(array_merge([$headers], $backups));

        return static::CODE_SUCCESS;
    }
}
