<?php
declare(strict_types=1);

namespace App\Test\TestCase\Command;

use App\Test\TestSuite\ConsoleIntegrationTestCase;

class AdminPasswordCommandTest extends ConsoleIntegrationTestCase
{
    public function getFixtures(): array
    {
        return [
            'app.Players',
        ];
    }

    public function testPasswordHelp(): void
    {
        $this->exec('admin password --help');
        $this->assertExitSuccess();

        $this->assertOutputContains('Set/remove user password.');
    }

    public function testPasswordNonExistantPlin(): void
    {
        $plin = 9999;

        $this->exec("admin password {$plin}");
        $this->assertExitError();

        $this->assertErrorContains("No player found with plin `{$plin}`.");
    }

    public function testPasswordUnchanged(): void
    {
        $this->exec('admin password 1', ['']);
        $this->assertExitSuccess();
        $this->assertOutputContains('Password unchanged');
    }

    public function testPasswordSetRemove(): void
    {
        $this->exec('admin password 1', ['secret']);
        $this->assertExitSuccess();
        $this->assertOutputContains('Password set');

        $this->exec('admin password --remove 1');
        $this->assertExitSuccess();
        $this->assertOutputContains('Password removed');
    }
}
