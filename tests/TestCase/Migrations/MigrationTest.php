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

    public function testRelationTable(): void
    {
        $tableName = 'test';
        $columnName = 'f00';

        $table = $this->getMockBuilder(Table::class)
            ->setConstructorArgs(['someTableName'])
            ->onlyMethods(['addColumnInteger', 'addIndex'])
            ->getMock();

        $table->expects($this->once())
            ->method('addColumnInteger')
            ->with(
                $this->equalTo($columnName),
            )
            ->willReturnSelf();

        $table->expects($this->once())
            ->method('addIndex')
            ->with(
                $this->equalTo([$columnName]),
            )
            ->willReturnSelf();

        $mock = $this->getMockBuilder(Migration::class)
            ->onlyMethods(['table'])
            ->getMock();

        $mock->expects($this->once())
            ->method('table')
            ->with(
                $this->equalTo($tableName),
                $this->equalTo(['id' => false, 'primary_key' => [$columnName]]),
            )
            ->willReturn($table);

        $table = $mock->relationTable($tableName, [$columnName]);
        $this->assertInstanceOf(Table::class, $table);
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
