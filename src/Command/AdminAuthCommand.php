<?php
declare(strict_types=1);

namespace App\Command;

use App\Command\Traits\CommandAuthorizationTrait;
use App\Model\Entity\Player;
use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;

class AdminAuthCommand extends Command
{
    use CommandAuthorizationTrait;

    protected ?string $defaultTable = 'Players';

    public static function defaultName(): string
    {
        return 'admin auth';
    }

    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser->setDescription('Show/modify users authorization.');
        $parser->addArgument(
            'plin',
            [ 'help' => 'Plin of the player to view/modify.'
                    , 'required' => false,
            ],
        );
        $parser->addArgument(
            'role',
            [ 'help' => 'Role to assign to player.'
                    , 'required' => false
                    , 'choices' => Player::roleValues(),
            ],
        );

        $parser->removeOption('quiet');
        $parser->removeOption('verbose');

        return $parser;
    }

    public function execute(Arguments $args, ConsoleIo $io): int
    {
        $plin = $args->getArgument('plin');
        $role = $args->getArgument('role');
        if (isset($plin)) {
            return $this->authPlayer($io, $plin, $role);
        }

        $query = $this->fetchTable()->find('list', valueField: 'id', groupField: 'role');
        $perms = $query->toArray();

        foreach (array_reverse(Player::roleValues()) as $role) {
            $count = isset($perms[$role]) ? count($perms[$role]) : 0;
            $io->out(sprintf('<warning>%s</warning> (%d)', $role, $count));
            if ($count == 0 || $count > 100) {
                continue;
            }

            foreach ($perms[$role] as $plin) {
                $player = $this->fetchTable()->get($plin);
                $io->out(sprintf('<info>%4d</info> %s', $plin, $player->fullName));
            }
        }

        return static::CODE_SUCCESS;
    }

    protected function authPlayer(ConsoleIo $io, string $plin, ?string $role): int
    {
        $table = $this->fetchTable();
        $player = $table->getMaybe($plin);
        if ($plin !== (string)$player?->id) {
            $io->abort(sprintf('No player found with plin `%s`.', $plin));
        }

        if (isset($role)) {
            $table->patchEntity($player, ['role' => $role]);
            $table->save($player);

            $errors = $player->getErrors('role');
            if (!empty($errors)) {
                foreach ($errors as $error) {
                    $io->err($error);
                }
                $this->abort();
            }
        }

        $io->out(sprintf(
            '<info>%04d</info> %s: <warning>%s</warning>',
            $player->id,
            $player->fullName,
            $player->role,
        ));

        return static::CODE_SUCCESS;
    }
}
