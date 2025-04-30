<?php
declare(strict_types=1);

use App\Migrations\Migration;

class DropDataTables extends Migration
{
    public function up(): void
    {
        $this->removeLookupTable('believes', 'belief_id', 'belief');
        $this->removeLookupTable('groups', 'group_id', 'group');
        $this->removeLookupTable('worlds', 'world_id', 'world');
    }

    protected function removeLookupTable(string $lookupTable, string $reference, string $name): void
    {
        $this->table('characters')
            ->addColumn($name, 'string', [
                'default' => '-',
                'limit' => 255,
                'null' => false,
                'after' => $reference,
            ])
            ->addIndex([$name])
            ->update();

        // fill text field
        $subQuery = $this->getQueryBuilder('select')
                    ->select('`name`')
                    ->from("`$lookupTable`")
                    ->where(["`id` = `characters`.`$reference`"]);
        $this->getQueryBuilder('update')
            ->update('`characters`')
            ->set(["`$name`" => $subQuery])
            ->execute();

        // drop foreign key fields
        $this->table('characters')
            ->dropForeignKey($reference)
            ->removeIndex($reference)
            ->removeColumn($reference)
            ->update();

        // drop lookup tables
        $this->table($lookupTable)->drop()->update();
    }

    public function down(): void
    {
        $this->restoreLookupTable('believes', 'belief_id', 'belief');
        $this->restoreLookupTable('groups', 'group_id', 'group');
        $this->restoreLookupTable('worlds', 'world_id', 'world');
    }

    protected function restoreLookupTable(string $lookupTable, string $reference, string $name): void
    {
        $this->table($lookupTable)
            ->addColumn('name', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn(
                'modified',
                'datetime',
                [ 'default' => null
                , 'limit' => null
                , 'null' => true,
                ],
            )
            ->addColumn(
                'modifier_id',
                'integer',
                [ 'default' => null
                , 'limit' => null
                , 'length' => 11
                , 'null' => true,
                ],
            )
            ->addIndex([ 'name' ])
            ->create();

        // fill lookup tables
        $subQuery = $this->getQueryBuilder('select')
                    ->select([$name])
                    ->distinct([$name])
                    ->from('characters')
                    ->orderAsc($name);
        $this->getQueryBuilder('insert')
            ->insert(['name'])
            ->into($lookupTable)
            ->values($subQuery)
            ->execute();

        // re-add foreign key fields
        $this->table('characters')
            ->addColumn($reference, 'integer', [
                'default' => 1,
                'limit' => 11,
                'null' => false,
                'after' => $name,
            ])
            ->update();
        $this->table('characters')
            ->addForeignKey(
                $reference,
                $lookupTable,
                'id',
                [ 'update' => 'CASCADE', 'delete' => 'RESTRICT' ],
            )
            ->update();

        // set foreign key fields
        $subQuery = $this->getQueryBuilder('select')
                    ->select('id')
                    ->from($lookupTable)
                    ->where(["name = characters.$name"]);
        $this->getQueryBuilder('update')
            ->update('characters')
            ->set([$reference => $subQuery])
            ->execute();

        // drop text fields
        $this->table('characters')
            ->removeIndex($name)
            ->removeColumn($name)
            ->update();
    }
}
