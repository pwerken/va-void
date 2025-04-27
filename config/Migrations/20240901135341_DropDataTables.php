<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class DropDataTables extends AbstractMigration
{
    public function up()
    {
        $this->_removeLookupTable('believes', 'belief_id', 'belief');
        $this->_removeLookupTable('groups', 'group_id', 'group');
        $this->_removeLookupTable('worlds', 'world_id', 'world');
    }

    protected function _removeLookupTable($lookupTable, $reference, $name)
    {
        $this->table('characters')
            ->addColumn($name, 'string', [
                'default' => '-',
                'limit' => 255,
                'null' => false,
                'after' => $reference,
            ])
            ->addIndex([$name])
            ->save();

        // fill text field
        $subQuery = $this->getQueryBuilder()
                    ->select("`name`")
                    ->from("`$lookupTable`")
                    ->where(["`id` = `characters`.`$reference`"]);
        $query = $this->getQueryBuilder()
                    ->update("`characters`")
                    ->set(["`$name`" => $subQuery])
                    ->execute();

        // drop foreign key fields
        $this->table('characters')
            ->dropForeignKey($reference)
            ->removeIndex($reference)
            ->removeColumn($reference)
            ->save();

        // drop lookup tables
        $this->table($lookupTable)->drop()->save();
    }

    public function down()
    {
        $this->_restoreLookupTable('believes', 'belief_id', 'belief');
        $this->_restoreLookupTable('groups', 'group_id', 'group');
        $this->_restoreLookupTable('worlds', 'world_id', 'world');
    }

    protected function _restoreLookupTable($lookupTable, $reference, $name)
    {
        $this->table($lookupTable)
            ->addColumn('name', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('modified', 'datetime',
                [ 'default' => null
                , 'limit' => null
                , 'null' => true
                ])
            ->addColumn('modifier_id', 'integer',
                [ 'default' => null
                , 'limit' => null
                , 'length' => 11
                , 'null' => true
                ])
            ->addIndex( [ 'name' ])
            ->create();

        // fill lookup tables
        $subQuery = $this->getQueryBuilder()
                    ->select([$name])
                    ->distinct([$name])
                    ->from("characters")
                    ->orderAsc($name);
        $query = $this->getQueryBuilder()
                    ->insert(["name"])
                    ->into($lookupTable)
                    ->values($subQuery)
                    ->execute();

        $default_id = 1;

        // re-add foreign key fields
        $this->table('characters')
            ->addColumn($reference, 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
                'after' => $name,
            ])
            ->update();
        $this->table('characters')
            ->addForeignKey(
                $reference,
                $lookupTable, 'id',
                [ 'update' => 'CASCADE', 'delete' => 'RESTRICT' ]
            )
            ->update();

        // set foreign key fields
        $subQuery = $this->getQueryBuilder()
                    ->select("id")
                    ->from($lookupTable)
                    ->where(["name = characters.$name"]);
        $query = $this->getQueryBuilder()
                    ->update('characters')
                    ->set([$reference => $subQuery])
                    ->execute();

        // drop text fields
        $this->table('characters')
            ->removeIndex($name)
            ->removeColumn($name)
            ->save();
    }
}
