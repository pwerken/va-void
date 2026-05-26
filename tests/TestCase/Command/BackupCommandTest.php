<?php
declare(strict_types=1);

namespace App\Test\TestCase\Command;

use App\Test\TestSuite\ConsoleIntegrationTestCase;
use Cake\Core\Configure;

class BackupCommandTest extends ConsoleIntegrationTestCase
{
    public function testBackupHelp(): void
    {
        $this->exec('backup --help');
        $this->assertExitSuccess();

        $this->assertOutputContains('List database backups.');
    }

    public function testBackupExportHelp(): void
    {
        $this->exec('backup export --help');
        $this->assertExitSuccess();

        $this->assertOutputContains('Create database backup.');
    }

    public function testBackupImportHelp(): void
    {
        $this->exec('backup import --help');
        $this->assertExitSuccess();

        $this->assertOutputContains('Restore database backup.');
    }

    public function testBackup(): void
    {
        $path = Configure::read('Backups.target');
        $file = '00000000000000_test.sql';
        $this->assertTrue(touch($path . $file));

        $this->exec('backup');
        $this->assertExitSuccess();

        $this->assertOutputContains('<info>Filename</info>');
        $this->assertOutputContains('<info>Size</info>');
        $this->assertOutputContains('<info>Datetime</info>');

        $this->assertOutputContains($file);

        $this->assertTrue(unlink($path . $file));
    }
}
