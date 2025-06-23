<?php
declare(strict_types=1);

namespace App\Test\TestCase\Command;

use App\Test\TestSuite\ConsoleIntegrationTestCase;

class AdminChecksCommandTest extends ConsoleIntegrationTestCase
{
    public function testChecksHelp(): void
    {
        $this->exec('admin checks --help');
        $this->assertExitSuccess();

        $this->assertOutputContains('Run application configuration and setup checks');
    }

    public function testChecks(): void
    {
        $this->exec('admin checks');
        $this->assertExitSuccess();

        $this->assertOutputContains('[X] ');
        $this->assertOutputNotContains('[ ] ');
    }
}
