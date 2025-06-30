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
            ->addColumnString($name, ['after' => $reference, 'default' => '-'])
            ->addIndex([$name])
            ->update();

        // fill text field
        $subQuery = $this->getSelectBuilder()
                    ->select('`name`')
                    ->from("`$lookupTable`")
                    ->where(["`id` = `characters`.`$reference`"]);
        $this->getUpdateBuilder()
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
            ->addColumnString('name')
            ->addColumnDateTime('modified')
            ->addColumnInteger('modifier_id', ['null' => true])
            ->addIndex(['name'])
            ->create();

        // fill lookup tables
        $subQuery = $this->getSelectBuilder()
                    ->select([$name])
                    ->distinct([$name])
                    ->from('characters')
                    ->orderAsc($name);
        $this->getInsertBuilder()
            ->insert(['name'])
            ->into($lookupTable)
            ->values($subQuery)
            ->execute();

        // re-add foreign key fields
        $this->table('characters')
            ->addColumn($reference, 'integer', ['after' => $name, 'default' => 1])
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
        $subQuery = $this->getSelectBuilder()
                    ->select('id')
                    ->from($lookupTable)
                    ->where(["name = characters.$name"]);
        $this->getUpdateBuilder()
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
