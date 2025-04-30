<?php
declare(strict_types=1);

namespace App\Command;

use App\Command\Traits\BackupTrait;
use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\Datasource\ConnectionManager;

class BackupExportCommand extends Command
{
    use BackupTrait;

    public static function defaultName(): string
    {
        return 'backup export';
    }

    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser->setDescription('Create database backup.');
        $parser->addArgument(
            'description',
            [ 'help' => 'Append to the created sql filename.'
                    , 'required' => false,
            ],
        );

        return $parser;
    }

    public function execute(Arguments $args, ConsoleIo $io): int
    {
        $app = $this->config('mysqldump');
        if (!$this->checkApp($app)) {
            $this->abort(sprintf('Error executing `%s`, check your config.', $app));
        }

        $target = $this->config('target');
        if (!is_writable($target)) {
            $this->abort(sprintf('Directory `%s` not writable', $target));
        }

        $descr = $args->getArgument('description');
        if (is_null($descr)) {
            $descr = '';
        } else {
            $descr = '_' . preg_replace('/[^a-zA-Z0-9]+/', '_', $descr);
        }
        $filename = sprintf('%s%s%s.sql', $target, date('YmdHis'), $descr);

        if (file_exists($filename)) {
            $this->abort(sprintf('File `%s` already exists', $filename));
        }

        $io->out('Exporting database content to file:');
        $io->quiet($filename);

        $connection = ConnectionManager::getConfig('default');
        $auth = $this->storeAuth($connection, 'mysqldump');
        $cmd = $this->config('mysqldump');
        $cmd .= ' --defaults-file=' . $auth;
        $cmd .= ' -t --result-file=' . $filename;
        $cmd .= ' --ignore-table=' . $connection['database'] . '.phinxlog';
        $cmd .= ' ' . $connection['database'];
        $io->verbose('exec: ' . $cmd);

        exec($cmd);
        unlink($auth);

        if (!file_exists($filename)) {
            $this->abort(sprintf('File `%s` not created', $filename));
        }

        if (filesize($filename) == 0) {
            $this->abort(sprintf('File `%s` is empty', $filename));
        }

        $io->out('Done');

        return static::CODE_SUCCESS;
    }
}
