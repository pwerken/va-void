<?php
declare(strict_types=1);

namespace App\Migrations;

use Migrations\Table as BaseTable;
use Phinx\Db\Table\Table as TableValue;

class Table extends BaseTable
{
    public function addForeignKey(
        string|array $columns,
        string|TableValue $referencedTable,
        string|array $referencedColumns = ['id'],
        array $options = [],
    ): static {
        if ($this->getAdapter()->getAdapterType() === 'sqlite') {
            return $this;
        }

        return parent::addForeignKey($columns, $referencedTable, $referencedColumns, $options);
    }

    public function addForeignKeyWithName(
        string $name,
        string|array $columns,
        string|TableValue $referencedTable,
        string|array $referencedColumns = ['id'],
        array $options = [],
    ): static {
        if ($this->getAdapter()->getAdapterType() === 'sqlite') {
            return $this;
        }

        return parent::addForeignKeyWithName($name, $columns, $referencedTable, $referencedColumns, $options);
    }

    public function addColumnBoolean(string $name): static
    {
        return $this->addColumn(
            $name,
            'boolean',
            [
                'default' => null,
                'limit' => null,
                'null' => false,
            ],
        );
    }

    public function addColumnDate(string $name): static
    {
        return $this->addColumn(
            $name,
            'date',
            [
                'default' => null,
                'limit' => null,
                'null' => true,
            ],
        );
    }

    public function addColumnDateTime(string $name): static
    {
        return $this->addColumn(
            $name,
            'datetime',
            [
                'default' => null,
                'limit' => null,
                'null' => true,
            ],
        );
    }

    public function addColumnInteger(string $name, bool $nullable = false): static
    {
        return $this->addColumn(
            $name,
            'integer',
            [
                'default' => null,
                'limit' => 11,
                'null' => $nullable,
            ],
        );
    }

    public function addColumnString(string $name, bool $nullable = false): static
    {
        return $this->addColumn(
            $name,
            'string',
            [
                'default' => null,
                'limit' => 255,
                'null' => $nullable,
            ],
        );
    }

    public function addColumnText(string $name, bool $nullable = false): static
    {
        return $this->addColumn(
            $name,
            'text',
            [
                'default' => null,
                'limit' => 255,
                'null' => $nullable,
            ],
        );
    }
}
