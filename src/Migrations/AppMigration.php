<?php
declare(strict_types=1);

namespace App\Migrations;

use Cake\I18n\DateTime;
use Migrations\AbstractMigration;
use Migrations\Table;

class AppMigration
    extends AbstractMigration
{
    protected $now;

    public function table(string $tableName, array $options = []): Table
    {
        if ($this->autoId === false) {
            $options['id'] = false;
        }
        $table = new AppTable($tableName, $options, $this->getAdapter());
        $this->tables[] = $table;

        return $table;
    }

    protected function now(): string
    {
        if($this->now === null) {
            $now = new DateTime();
            $this->now = $now->toDateTimeString();
        }

        return $this->now;
    }
}
