<?php
declare(strict_types=1);

namespace App\Test\TestCase\Command;

use App\Test\TestSuite\ConsoleIntegrationTestCase;
use PHPUnit\Framework\Attributes\DataProvider;

class AdminAuthCommandTest extends ConsoleIntegrationTestCase
{
    public function getFixtures(): array
    {
        return [
            'app.Players',
        ];
    }

    public function testAuthHelp(): void
    {
        $this->exec('admin auth --help');
        $this->assertExitSuccess();

        $this->assertOutputContains('Show/modify users authorization');
    }

    public function testAuth(): void
    {
        $this->exec('admin auth');
        $this->assertExitSuccess();

        $this->assertOutputRegExp('#\\<warning\\>Super.*\\([0-9]+\\)#');
        $this->assertOutputRegExp('#\\<warning\\>Event Control.*\\([0-9]+\\)#');
        $this->assertOutputRegExp('#\\<warning\\>Infobalie.*\\([0-9]+\\)#');
        $this->assertOutputRegExp('#\\<warning\\>Referee.*\\([0-9]+\\)#');
        $this->assertOutputRegExp('#\\<warning\\>Read-only.*\\([0-9]+\\)#');
        $this->assertOutputRegExp('#\\<warning\\>Player.*\\([0-9]+\\)#');
    }

    public static function playersWithAuth(): array
    {
        return [
            [1, 'Player One', 'Player'],
            [2, 'Read Only', 'Read-only'],
            [3, 'Centrale Spelleiding', 'Referee'],
            [4, 'In fo Balie', 'Infobalie'],
            [5, 'Super User', 'Super'],
        ];
    }

    #[DataProvider('playersWithAuth')]
    public function testAuthPlin(int $plin, string $name, string $auth): void
    {
        $this->exec("admin auth {$plin}");
        $this->assertExitSuccess();

        $expected = sprintf('<info>%04d</info> %s: <warning>%s</warning>', $plin, $name, $auth);
        $this->assertOutputContains($expected);
    }

    public function testAuthNonExistantPlin(): void
    {
        $plin = 9999;

        $this->exec("admin auth {$plin}");
        $this->assertExitError();

        $expected = sprintf('No player found with plin `%s`.', $plin);
        $this->assertErrorContains($expected);
    }

    public function testAuthPlinSetRole(): void
    {
        $plin = 6;
        $auth = 'Super';

        $this->exec("admin auth {$plin} {$auth}");
        $this->assertExitSuccess();

        $expected = sprintf('<info>%04d</info> %s: <warning>%s</warning>', $plin, 'no login', $auth);
        $this->assertOutputContains($expected);
    }

    public function testAuthPlinSetRoleFailure(): void
    {
        $plin = 6;
        $auth = 'ERROR';

        $this->exec("admin auth {$plin} {$auth}");
        $this->assertExitError();

        $expected = sprintf('`%s` is not a valid value for `role`.', $auth);
        $this->assertErrorContains($expected);
    }
}
