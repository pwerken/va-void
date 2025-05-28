<?php
declare(strict_types=1);

namespace App\Migrations;

use Cake\I18n\DateTime;
use Migrations\AbstractMigration;

class Migration extends AbstractMigration
{
    protected ?string $now = null;

    public function table(string $tableName, array $options = []): Table
    {
        $table = new Table($tableName, $options, $this->getAdapter());
        $this->tables[] = $table;

        return $table;
    }

    public function relationTable(string $tableName, array $keys): Table
    {
        $table = $this->table($tableName, ['id' => false, 'primary_key' => $keys]);

        foreach ($keys as $key) {
            $table->addColumnInteger($key)->addIndex([$key]);
        }

        return $table;
    }

    protected function now(): string
    {
        if (is_null($this->now)) {
            $now = new DateTime();
            $this->now = $now->toDateTimeString();
        }

        return $this->now;
    }
}
