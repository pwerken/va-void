<?php
declare(strict_types=1);

namespace App\Test\Migrations;

use App\Migrations\Migration;
use App\Test\TestSuite\TestCase;
use ReflectionClass;

class MigrationTest extends TestCase
{
    public function testNow(): void
    {
        $migration = new Migration('', 12345678901234);

        $reflection = new ReflectionClass($migration);
        $method = $reflection->getMethod('now');
        $method->setAccessible(true);

        $property = $reflection->getProperty('now');
        $property->setAccessible(true);

        $this->assertNull($property->getValue($migration));

        $result = $method->invoke($migration);
        $this->assertStringMatchesFormat('%d-%d-%d %d:%d:%d', $result);

        $this->assertNotNull($property->getValue($migration));
    }
}
