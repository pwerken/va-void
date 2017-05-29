<?php
use Migrations\AbstractMigration;

class Events extends AbstractMigration
{

    public function up()
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

    public function down()
    {

        $this->dropTable('events');
    }
}

