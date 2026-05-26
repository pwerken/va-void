<?php
declare(strict_types=1);

namespace App\Test\TestCase\Command\Traits;

use App\Command\Traits\BackupTrait;
use App\Test\TestSuite\TestCase;

class BackupTraitTest extends TestCase
{
    use BackupTrait;

    public function testConfig(): void
    {
        $this->assertSame(ROOT . DS . 'backups' . DS, $this->config('target'));
    }

    public function testCheckApp(): void
    {
        $this->assertTrue($this->checkApp('ls'));
    }

    public function testGetBackupFiles(): void
    {
        $path = $this->config('target');
        $file = '00000000000000_test.sql';
        $this->assertTrue(touch($path . $file));

        $list = $this->getBackupFiles();

        $this->assertTrue(unlink($path . $file));

        foreach ($list as $i => $row) {
            $this->assertIsArray($row);
            $this->assertCount(3, $row);

            if ($i == 0) {
                $this->assertEquals($file, $row[0]);
                $this->assertEquals(0, $row[1]);
            }
        }
    }

    public function testStoreAuth(): void
    {
        $conn = [
            'username' => 'f00',
            'password' => 'b4r',
            'host' => 'qux',
        ];
        $app = 'fred';

        $file = $this->storeAuth($conn, $app);

        $this->assertFileExists($file);
        $this->assertFileIsReadable($file);
        $this->assertTrue(unlink($file));
    }

    public function testTablesTruncateOrder(): void
    {
        $tables = $this->tablesTruncateOrder();

        foreach ($tables as $table) {
            $this->assertIsString($table);
        }
    }
}
