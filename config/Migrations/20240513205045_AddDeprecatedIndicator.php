<?php
declare(strict_types=1);

use App\Migrations\AppMigration;

class AddDeprecatedIndicator extends AppMigration
{
    public function up()
    {
        $this->table('conditions')
            ->addColumn('deprecated', 'boolean',
                [ 'default' => false
                , 'limit' => null
                , 'null' => false
                , 'after' => 'notes'
                ])
            ->save();

        $this->table('powers')
            ->addColumn('deprecated', 'boolean',
                [ 'default' => false
                , 'limit' => null
                , 'null' => false
                , 'after' => 'notes'
                ])
            ->save();

        $this->table('items')
            ->addColumn('deprecated', 'boolean',
                [ 'default' => false
                , 'limit' => null
                , 'null' => false
                , 'after' => 'expiry'
                ])
            ->save();
    }

    public function down()
    {
        $this->table('conditions')
            ->removeColumn('deprecated')
            ->save();

        $this->table('powers')
            ->removeColumn('deprecated')
            ->save();

        $this->table('items')
            ->removeColumn('deprecated')
            ->save();
    }
}
