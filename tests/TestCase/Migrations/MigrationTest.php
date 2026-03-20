<?php
declare(strict_types=1);

namespace App\Test\Migrations;

use App\Migrations\Migration;
use App\Migrations\Table;
use App\Test\TestSuite\TestCase;
use Migrations\Db\Adapter\AdapterInterface;
use ReflectionClass;

class MigrationTest extends TestCase
{
    public function testTable(): void
    {
        $sqlite = $this->createStub(AdapterInterface::class);
        $sqlite->method('getAdapterType')->willReturn('sqlite');

        $migration = new Migration(12345678901234);
        $migration->setAdapter($sqlite);

        $table = $migration->table('test');
        $this->assertInstanceOf(Table::class, $table);
        $this->assertEquals('test', $table->getName());
    }

    public function testNow(): void
    {
        $migration = new Migration(12345678901234);

        $reflection = new ReflectionClass($migration);

        $property = $reflection->getProperty('now');
        $this->assertNull($property->getValue($migration));

        $method = $reflection->getMethod('now');
        $result = $method->invoke($migration);
        $this->assertStringMatchesFormat('%d-%d-%d %d:%d:%d', $result);

        $this->assertNotNull($property->getValue($migration));
    }
}
