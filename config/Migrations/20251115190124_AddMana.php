<?php
declare(strict_types=1);

use App\Migrations\Migration;

class AddMana extends Migration
{
    public function up(): void
    {
        $this->table('powers')
            ->addColumnInteger('manatype_id', ['after' => 'notes', 'null' => true])
            ->addColumnInteger('mana_amount', ['after' => 'manatype_id', 'null' => true])
            ->addForeignKey('manatype_id', 'manatypes')
            ->update();

        $this->table('conditions')
            ->addColumnInteger('manatype_id', ['after' => 'notes', 'null' => true])
            ->addColumnInteger('mana_amount', ['after' => 'manatype_id', 'null' => true])
            ->addForeignKey('manatype_id', 'manatypes')
            ->update();

        $this->table('items')
            ->addColumnInteger('manatype_id', ['after' => 'notes', 'null' => true])
            ->addColumnInteger('mana_amount', ['after' => 'manatype_id', 'null' => true])
            ->addForeignKey('manatype_id', 'manatypes')
            ->update();
    }

    public function down(): void
    {
        $this->table('powers')
            ->removeColumn('manatype_id')
            ->removeColumn('mana_amount')
            ->dropForeignKey('manatype_id')
            ->update();

        $this->table('conditions')
            ->removeColumn('manatype_id')
            ->removeColumn('mana_amount')
            ->dropForeignKey('manatype_id')
            ->update();

        $this->table('items')
            ->removeColumn('manatype_id')
            ->removeColumn('mana_amount')
            ->dropForeignKey('manatype_id')
            ->update();
    }
}
