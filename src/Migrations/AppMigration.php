<?php
declare(strict_types=1);

namespace App\Migrations;

use Migrations\AbstractMigration;
use Migrations\Table;

class AppMigration
    extends AbstractMigration
{
    public function table(string $tableName, array $options = []): Table
    {
        if ($this->autoId === false) {
            $options['id'] = false;
        }

        return new AppTable($tableName, $options, $this->getAdapter());
    }
}
