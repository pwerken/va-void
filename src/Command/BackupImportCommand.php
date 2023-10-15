<?php
declare(strict_types=1);

namespace App\Command;

use Cake\Command\CacheClearallCommand;
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

        $io->out('Truncating database tables...');
        $tables = $this->tablesTruncateOrder();
        $existing = ConnectionManager::get('default')
                        ->getSchemaCollection()
                        ->listTables();
        $sql = 'ALTER TABLE `%s` auto_increment = 1;';
        foreach($tables as $table) {
            if(!in_array($table, $existing)) {
                $io->warning("! $table ");
                continue;
            }

            $io->verbose("- $table");
            $model = $this->fetchModel($table);
            $model->deleteQuery()->execute();
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

        $io->out('Clearing cache...');
        $cache_args = [];
        if($io->level() >= ConsoleIo::VERBOSE) {
            $cache_args[] = '-v';
        } else {
            $cache_args[] = '-q';
        }
        $this->executeCommand(CacheClearallCommand::class, $cache_args, $io);

        $io->out('Done');
        return static::CODE_SUCCESS;
    }
}
