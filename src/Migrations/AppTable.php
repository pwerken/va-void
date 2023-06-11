<?php
declare(strict_types=1);

namespace App\Migrations;

use Migrations\Table;

class AppTable
    extends Table
{
    public function addForeignKey($columns, $referencedTable, $referencedColumns = ['id'], array $options = [])
    {
        if ($this->getAdapter()->getAdapterType() === 'sqlite') {
            return $this;
        }

        return parent::addForeignKey($columns, $referencedTable, $referencedColumns, $options);
    }

    public function addForeignKeyWithName(string $name, $columns, $referencedTable, $referencedColumns = ['id'], array $options = [])
    {
        if ($this->getAdapter()->getAdapterType() === 'sqlite') {
            return $this;
        }

        return parent::addForeignKeyWithName($name, $columns, $referencedTable, $referencedColumns, $options);
    }
}
