<?php
declare(strict_types=1);

namespace App\Command;

use App\Utility\CheckConfig;
use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;

class AdminChecksCommand extends Command
{
    public static function defaultName(): string
    {
        return 'admin checks';
    }

    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser->setDescription('Run application configuration and setup checks');

        $parser->removeOption('quiet');
        $parser->removeOption('verbose');

        return $parser;
    }

    public function execute(Arguments $args, ConsoleIo $io): int
    {
        $checks = CheckConfig::installation();

        $hasError = false;
        foreach ($checks as $check => $ok) {
            if ($ok) {
                $io->out($check);
                continue;
            }
            $io->err($check);
            $hasError = true;
        }

        if ($hasError) {
            $this->abort();
        }

        return static::CODE_SUCCESS;
    }
}
