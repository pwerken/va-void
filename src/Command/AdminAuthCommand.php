<?php
declare(strict_types=1);

namespace App\Command;

use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;

use App\Command\Traits\CommandAuthorization;
use App\Model\Entity\Player;

class AdminAuthCommand
    extends Command
{
    use CommandAuthorization;

    protected ?string $defaultTable = 'Players';

    public static function defaultName(): string
    {
        return 'admin auth';
    }

    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser->setDescription('Show/modify users authorization.');
        $parser->addArgument('plin',
                    [ 'help' => 'Plin of the player to view/modify.'
                    , 'required' => false
                    ]);
        $parser->addArgument('role',
                    [ 'help' => 'Role to assign to player.'
                    , 'required' => false
                    , 'choices' => Player::roleValues()
                    ]);

        $parser->removeOption('quiet');
        $parser->removeOption('verbose');
        return $parser;
    }

    public function execute(Arguments $args, ConsoleIo $io): int
    {
        $plin = $args->getArgument('plin');
        $role = $args->getArgument('role');
        if(isset($plin)) {
            return $this->authPlayer($io, $plin, $role);
        }

        $perms = $this->fetchTable()->find('list',
                    [ 'valueField' => 'id'
                    , 'groupField' => 'role'
                    ])->toArray();

        foreach(array_reverse(Player::roleValues()) as $role)
        {
            $count = isset($perms[$role]) ? count($perms[$role]) : 0;
            $io->out(sprintf('<warning>%s</warning> (%d)', $role, $count));
            if ($count == 0 || $count > 100) {
                continue;
            }

            foreach($perms[$role] as $plin) {
                $player = $this->fetchTable()->get($plin);
                $io->out(sprintf('<info>%4d</info> %s', $plin, $player->fullName));
            }
        }
        return static::CODE_SUCCESS;
    }

    protected function authPlayer($io, $plin, $role): int
    {
        $table = $this->fetchTable();
        $player = $table->findById($plin)->first();
        if (is_null($player) || strcmp((string)$player->id, $plin)) {
            $this->abort(sprintf('No player found with plin `%s`.', $plin));
        }

        if (isset($role)) {
            $table->patchEntity($player, ['role' => $role]);
            $table->save($player);

            $errors = $player->getErrors('role');
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
                    , $player->role
                    ));
        return static::CODE_SUCCESS;
    }
}
