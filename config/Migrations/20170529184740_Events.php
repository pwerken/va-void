<?php
declare(strict_types=1);

use App\Migrations\AppMigration;

class Events extends AppMigration
{
    public function up(): void
    {
        $this->table('events')
            ->addColumn('name', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('modified', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addIndex(
                [
                    'name',
                ]
            )
            ->create();
    }

    public function down(): void
    {
        $this->dropTable('events');
    }
}
