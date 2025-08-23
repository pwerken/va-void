<?php
declare(strict_types=1);

namespace App\Command;

use App\Command\Traits\CommandAuthorizationTrait;
use App\Model\Enum\PlayerRole;
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
                    , 'choices' => PlayerRole::values(),
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

        $grouper = function ($value, $key) {
            return $value->role->value;
        };

        $perms = $this->fetchTable()
                    ->find('list', valueField: 'plin', groupField: $grouper)
                    ->all()
                    ->toArray();

        foreach (array_reverse(PlayerRole::cases()) as $role) {
            $count = isset($perms[$role->value]) ? count($perms[$role->value]) : 0;
            $io->out(sprintf('<warning>%s</warning> (%d)', $role->label(), $count));
            if ($count == 0 || $count > 100) {
                continue;
            }

            foreach ($perms[$role->value] as $plin) {
                $player = $this->fetchTable()->get($plin);
                $io->out(sprintf('<info>%4d</info> %s', $plin, $player->get('name')));
            }
        }

        return static::CODE_SUCCESS;
    }

    protected function authPlayer(ConsoleIo $io, string $plin, ?string $role): int
    {
        $table = $this->fetchTable();
        $player = $table->getMaybe($plin);
        if ($plin !== (string)$player?->get('plin')) {
            $io->abort(sprintf('No player found with plin `%s`.', $plin));
        }

        if (isset($role)) {
            $table->patchEntity($player, ['role' => PlayerRole::tryFrom($role)]);
            $table->save($player);

            $errors = $player->getError('role');
            if (!empty($errors)) {
                foreach ($errors as $error) {
                    $io->err($error);
                }
                $this->abort();
            }
        }

        $io->out(sprintf(
            '<info>%04d</info> %s: <warning>%s</warning>',
            $player->get('plin'),
            $player->get('name'),
            $player->get('role')->label(),
        ));

        return static::CODE_SUCCESS;
    }
}
