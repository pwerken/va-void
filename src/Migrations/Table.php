<?php
declare(strict_types=1);

namespace App\Migrations;

use Migrations\Db\Table as BaseTable;
use Migrations\Db\Table\ForeignKey;
use Migrations\Db\Table\TableMetadata;

class Table extends BaseTable
{
    protected array $columnDefaults = [
        'default' => null,
        'limit' => null,
        'null' => false,
    ];

    public function addForeignKey(
        string|array|ForeignKey $columns,
        string|TableMetadata|null $referencedTable = null,
        string|array $referencedColumns = ['id'],
        array $options = [],
    ): static {
        if ($this->getAdapter()->getAdapterType() === 'sqlite') {
            return $this;
        }

        return parent::addForeignKey($columns, $referencedTable, $referencedColumns, $options);
    }

    public function dropForeignKey(
        string|array $columns,
        ?string $constraint = null,
    ): static {
        if ($this->getAdapter()->getAdapterType() === 'sqlite') {
            return $this;
        }

        return parent::dropForeignKey($columns, $constraint);
    }

    public function addColumnBoolean(string $name, array $options = []): static
    {
        return $this->addColumn(
            $name,
            'boolean',
            array_merge($this->columnDefaults, ['default' => false], $options),
        );
    }

    public function addColumnDate(string $name, array $options = []): static
    {
        return $this->addColumn(
            $name,
            'date',
            array_merge($this->columnDefaults, ['null' => true], $options),
        );
    }

    public function addColumnDateTime(string $name, array $options = []): static
    {
        return $this->addColumn(
            $name,
            'datetime',
            array_merge($this->columnDefaults, ['null' => true], $options),
        );
    }

    public function addColumnInteger(string $name, array $options = []): static
    {
        return $this->addColumn(
            $name,
            'integer',
            array_merge($this->columnDefaults, ['limit' => 11], $options),
        );
    }

    public function addColumnString(string $name, array $options = []): static
    {
        return $this->addColumn(
            $name,
            'string',
            array_merge($this->columnDefaults, ['limit' => 255], $options),
        );
    }

    public function addColumnText(string $name, array $options = []): static
    {
        return $this->addColumn(
            $name,
            'text',
            $options,
        );
    }
}
