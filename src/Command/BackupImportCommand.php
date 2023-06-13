<?php
declare(strict_types=1);

namespace App\Command;

use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\Datasource\ConnectionManager;
use Cake\Filesystem\Folder;

use App\Command\Traits\Backup;

class BackupImportCommand
    extends Command
{
    use Backup;

    public static function defaultName(): string
    {
        return 'backup import';
    }

    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser->setDescription('Restore database backup.');
        $parser->addArgument('filename',
                    [ 'help' => 'Backup to restore.'
                    , 'required' => true
                    ]);

        return $parser;
    }

    public function execute(Arguments $args, ConsoleIo $io): int
    {
        $this->checkApp($this->config('mysql'));

        $filename = $args->getArgument('filename');
        if(!Folder::isAbsolute($filename)) {
            $filename = $this->config('target') . $filename;
        }

        if(!file_exists($filename)) {
            $this->abort(sprintf('File `%s` does not exists', $filename));
        }

        if(!is_readable($filename)) {
            $this->abort(sprintf('File `%s` not readable', $filename));
        }

        if(filesize($filename) == 0) {
            $this->abort(sprintf('File `%s` is empty', $filename));
        }

        $tables = $this->tablesTruncateOrder();

        $io->out('Truncating database tables...');
        $sql = 'ALTER TABLE `%s` auto_increment = 1;';
        foreach($tables as $table) {
            $io->verbose("- $table");

            $model = $this->loadModel($table);
            $model->query()->delete()->execute();
            $model->getConnection()->execute(sprintf($sql, $model->getTable()));
        }

        $io->out('Importing database content from file:');
        $io->quiet($filename);

        $connection = ConnectionManager::getConfig('default');
        $auth = $this->storeAuth($connection, 'mysql');
        $cmd = sprintf('%s --defaults-extra-file=%s %s < %s'
                    , $this->config('mysql'), $auth
                    , $connection['database'], $filename);
        $io->verbose('exec: '.$cmd);

        exec($cmd);
        unlink($auth);

        $io->out('Done');
        return static::CODE_SUCCESS;
    }
}
