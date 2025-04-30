<?php
declare(strict_types=1);

use App\Migrations\Migration;

class AddDeprecatedIndicator extends Migration
{
    public function up(): void
    {
        $this->table('conditions')
            ->addColumn(
                'deprecated',
                'boolean',
                [ 'default' => false
                , 'limit' => null
                , 'null' => false
                , 'after' => 'notes',
                ],
            )
            ->update();

        $this->table('powers')
            ->addColumn(
                'deprecated',
                'boolean',
                [ 'default' => false
                , 'limit' => null
                , 'null' => false
                , 'after' => 'notes',
                ],
            )
            ->update();

        $this->table('items')
            ->addColumn(
                'deprecated',
                'boolean',
                [ 'default' => false
                , 'limit' => null
                , 'null' => false
                , 'after' => 'expiry',
                ],
            )
            ->update();
    }

    public function down(): void
    {
        $this->table('conditions')->removeColumn('deprecated')->update();
        $this->table('powers')->removeColumn('deprecated')->update();
        $this->table('items')->removeColumn('deprecated')->update();
    }
}
