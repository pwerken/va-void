<?php
declare(strict_types=1);

namespace App\Command;

use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;

use App\Command\Traits\CommandAuthorization;
use App\Model\Entity\Player;

class AdminPasswordCommand
    extends Command
{
    use CommandAuthorization;

    protected ?string $defaultTable = 'Players';

    public static function defaultName(): string
    {
        return 'admin password';
    }

    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser->setDescription('Set/remove user password.');
        $parser->addArgument('plin',
                    [ 'help' => 'Plin of the player to view/modify.'
                    , 'required' => true
                    ]);
        $parser->addOption('remove',
                    [ 'help' => 'Remove password instead of setting it.'
                    , 'required' => false
                    , 'boolean' => true
                    ]);

        $parser->removeOption('verbose');
        return $parser;
    }

    public function execute(Arguments $args, ConsoleIo $io): int
    {
        $plin = $args->getArgument('plin');
        $remove = $args->getOption('remove');

        $table = $this->fetchTable();
        $player = $table->findById($plin)->first();
        if(is_null($player) || strcmp((string)$player->id, $plin)) {
            $this->abort(sprintf('No player found with plin `%s`.', $plin));
        }

        $new_password = false;
        if($remove) {
            $new_password = NULL;
            $msg = 'Password removed';
        } else {
            $new_password = $this->silent_prompt($io);
            $msg = 'Password set';
            if(empty($new_password)) {
                $new_password = false;
            }
        }

        if($new_password === false) {
            $msg = 'Password unchanged';
        } else {
            $player->set('password', $new_password);
            $table->save($player);
            $errors = $player->getErrors('password');
            if(!empty($errors)) {
                foreach($errors as $error) {
                    $this->err($error);
                }
                $this->abort();
            }
        }

        $io->out(sprintf('<info>%04d</info> %s: <warning>%s</warning>'
                    , $player->id
                    , $player->fullName
                    , $msg
                    ));
        return static::CODE_SUCCESS;
    }

    /**
     * Interactively prompts for input without echoing to the terminal.
     * Requires a bash shell or Windows and won't work with
     * safe_mode settings (Uses `shell_exec`)
     */
    protected function silent_prompt($io, $prompt = "Enter Password:")
    {
        if (preg_match('/^win/i', PHP_OS)) {
            $vbscript = sys_get_temp_dir() . 'prompt_password.vbs';
            file_put_contents(
                $vbscript, 'wscript.echo(InputBox("'
                . addslashes($prompt)
                . '", "", "password here"))');
            $command = "cscript //nologo " . escapeshellarg($vbscript);
            $password = rtrim(shell_exec($command));
            unlink($vbscript);
            return $password;
        } else {
            $io->out('<question>'.$prompt.'</question>');
            $command = "/usr/bin/env bash -c 'read -s -p \"\" mypassword && echo \$mypassword'";
            $password = rtrim(shell_exec($command));
            return $password;
        }
    }
}
