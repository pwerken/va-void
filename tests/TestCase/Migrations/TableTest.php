<?php
declare(strict_types=1);

namespace App\Test\Migrations;

use App\Migrations\Table;
use App\Test\TestSuite\TestCase;

class TableTest extends TestCase
{
    public function testAddColumnBoolean(): void
    {
        $columnName = 'testColumnName';
        $options = [
            'default' => null,
            'limit' => null,
            'null' => false,
        ];

        $mock = $this->getMockBuilder(Table::class)
            ->setConstructorArgs(['someTableName'])
            ->onlyMethods(['addColumn'])
            ->getMock();

        $mock->expects($this->once())
            ->method('addColumn')
            ->with(
                $this->equalTo($columnName),
                $this->equalTo('boolean'),
                $this->equalTo($options),
            )
            ->willReturnSelf();

        $result = $mock->addColumnBoolean($columnName);
        $this->assertEquals($mock, $result);
    }

    public function testAddColumnDate(): void
    {
        $columnName = 'testColumnName';
        $options = [
            'default' => null,
            'limit' => null,
            'null' => true,
        ];

        $mock = $this->getMockBuilder(Table::class)
            ->setConstructorArgs(['someTableName'])
            ->onlyMethods(['addColumn'])
            ->getMock();

        $mock->expects($this->once())
            ->method('addColumn')
            ->with(
                $this->equalTo($columnName),
                $this->equalTo('date'),
                $this->equalTo($options),
            )
            ->willReturnSelf();

        $result = $mock->addColumnDate($columnName);
        $this->assertEquals($mock, $result);
    }

    public function testAddColumnDateTime(): void
    {
        $columnName = 'testColumnName';
        $options = [
            'default' => null,
            'limit' => null,
            'null' => true,
        ];

        $mock = $this->getMockBuilder(Table::class)
            ->setConstructorArgs(['someTableName'])
            ->onlyMethods(['addColumn'])
            ->getMock();

        $mock->expects($this->once())
            ->method('addColumn')
            ->with(
                $this->equalTo($columnName),
                $this->equalTo('datetime'),
                $this->equalTo($options),
            )
            ->willReturnSelf();

        $result = $mock->addColumnDateTime($columnName);
        $this->assertEquals($mock, $result);
    }

    public function testAddColumnInteger(): void
    {
        $columnName = 'testColumnName';
        $options = [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ];

        $mock = $this->getMockBuilder(Table::class)
            ->setConstructorArgs(['someTableName'])
            ->onlyMethods(['addColumn'])
            ->getMock();

        $mock->expects($this->once())
            ->method('addColumn')
            ->with(
                $this->equalTo($columnName),
                $this->equalTo('integer'),
                $this->equalTo($options),
            )
            ->willReturnSelf();

        $result = $mock->addColumnInteger($columnName);
        $this->assertEquals($mock, $result);
    }

    public function testAddColumnString(): void
    {
        $columnName = 'testColumnName';
        $options = [
            'default' => null,
            'limit' => 255,
            'null' => false,
        ];

        $mock = $this->getMockBuilder(Table::class)
            ->setConstructorArgs(['someTableName'])
            ->onlyMethods(['addColumn'])
            ->getMock();

        $mock->expects($this->once())
            ->method('addColumn')
            ->with(
                $this->equalTo($columnName),
                $this->equalTo('string'),
                $this->equalTo($options),
            )
            ->willReturnSelf();

        $result = $mock->addColumnString($columnName);
        $this->assertEquals($mock, $result);
    }

    public function testAddColumnText(): void
    {
        $columnName = 'testColumnName';

        $mock = $this->getMockBuilder(Table::class)
            ->setConstructorArgs(['someTableName'])
            ->onlyMethods(['addColumn'])
            ->getMock();

        $mock->expects($this->once())
            ->method('addColumn')
            ->with(
                $this->equalTo($columnName),
                $this->equalTo('text'),
                $this->equalTo([]),
            )
            ->willReturnSelf();

        $result = $mock->addColumnText($columnName);
        $this->assertEquals($mock, $result);
    }
}
