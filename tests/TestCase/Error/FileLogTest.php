<?php
declare(strict_types=1);

namespace App\Test\Error;

use App\Error\FileLog;
use App\Test\TestSuite\TestCase;

class FileLogTest extends TestCase
{
    public function testLogrotate(): void
    {
        $mock = $this->getMockBuilder(FileLog::class)
            ->onlyMethods(['log'])
            ->getMock();

        $mock->expects($this->once())
            ->method('log');

        $mock->logrotate();
    }
}
