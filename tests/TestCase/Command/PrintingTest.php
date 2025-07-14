<?php
declare(strict_types=1);

namespace App\Test\TestCase\Command;

use App\Test\TestSuite\ConsoleIntegrationTestCase;

class PrintingTest extends ConsoleIntegrationTestCase
{
    public function getFixtures(): array
    {
        return [
            'app.Players',
            'app.Characters',
        ];
    }

    public function testCommandLinePDF(): void
    {
        # start with empty print queue
        $this->exec('queue');
        $this->assertExitSuccess();
        $this->assertOutputContains('0');

        # should not work
        $this->exec('queue single 0');
        $this->assertExitError();
        $this->assertErrorContains('Error generating pdf');
        $this->exec('queue double 0');
        $this->assertExitError();
        $this->assertErrorContains('Error generating pdf');

        # fill the queue
        $this->fetchTable('Lammies')
            ->insertQuery()
            ->insert(['status', 'entity', 'key1'])
            ->values(['status' => 'Queued', 'entity' => 'Character', 'key1' => 1])
            ->execute();

        # check queue size
        $this->exec('queue');
        $this->assertExitSuccess();
        $this->assertOutputContains('1');

        # get pdf for single-sided printing
        $this->exec('queue single 1');
        $this->assertExitSuccess();
        $this->assertOutputContains('%PDF');

        # get pdf for double-sided printing
        $this->exec('queue double 1');
        $this->assertExitSuccess();
        $this->assertOutputContains('%PDF');

        # check print queue is unchanged
        $this->exec('queue');
        $this->assertExitSuccess();
        $this->assertOutputContains('1');

        # mark everything printed
        $this->exec('queue printed 1');
        $this->assertExitSuccess();

        # check print queue is empty again
        $this->exec('queue');
        $this->assertExitSuccess();
        $this->assertOutputContains('0');

        # should not work
        $this->exec('queue single 1');
        $this->assertExitError();
        $this->assertErrorContains('Error generating pdf');
        $this->exec('queue double 1');
        $this->assertExitError();
        $this->assertErrorContains('Error generating pdf');
    }
}
