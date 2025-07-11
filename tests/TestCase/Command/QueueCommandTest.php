<?php
declare(strict_types=1);

namespace App\Test\TestCase\Command;

use App\Test\TestSuite\ConsoleIntegrationTestCase;

class QueueCommandTest extends ConsoleIntegrationTestCase
{
    public function testQueueHelp(): void
    {
        $this->exec('queue --help');
        $this->assertExitSuccess();

        $this->assertOutputContains('ID of the last unprinted lammy');
    }

    public function testQueueSingleHelp(): void
    {
        $this->exec('queue single --help');
        $this->assertExitSuccess();

        $this->assertOutputContains('Create a PDF for single-sided printing.');
    }

    public function testQueueDoubleHelp(): void
    {
        $this->exec('queue double --help');
        $this->assertExitSuccess();

        $this->assertOutputContains('Create a PDF for double-sided printing.');
    }

    public function testQueuePrintedHelp(): void
    {
        $this->exec('queue printed --help');
        $this->assertExitSuccess();

        $this->assertOutputContains('Mark queued lammies as printed.');
    }
}
