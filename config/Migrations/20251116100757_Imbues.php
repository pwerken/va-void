<?php
declare(strict_types=1);

use App\Migrations\Migration;

class Imbues extends Migration
{
    public function up(): void
    {
        $this->table('imbues')
            ->addColumnString('name')
            ->addColumnInteger('cost')
            ->addColumnText('description')
            ->addColumnText('notes', ['null' => true])
            ->addColumnInteger('times_max', ['default' => 1])
            ->addColumnInteger('manatype_id', ['null' => true])
            ->addColumnInteger('mana_amount', ['null' => true])
            ->addColumnBoolean('deprecated')
            ->addColumnDateTime('modified')
            ->addColumnInteger('modifier_id', ['null' => true])
            ->addIndex(['name'])
            ->addForeignKey('manatype_id', 'manatypes')
            ->create();

        $this->table('characters_imbues', [
                'id' => false,
                'primary_key' => ['character_id', 'imbue_id', 'type'],
            ])
            ->addColumnInteger('character_id')
            ->addColumnInteger('imbue_id')
            ->addColumnString('type')
            ->addColumnInteger('times', ['default' => 1])
            ->addColumnDateTime('modified')
            ->addColumnInteger('modifier_id', ['null' => true])
            ->addIndex(['character_id'])
            ->addIndex(['imbue_id'])
            ->addIndex(['type'])
            ->addForeignKey('character_id', 'characters')
            ->addForeignKey('imbue_id', 'imbues')
            ->create();
    }

    public function down(): void
    {
        $this->table('characters_imbues')
            ->dropForeignKey('character_id')
            ->dropForeignKey('imbue_id')
            ->update();
        $this->table('imbues')
            ->dropForeignKey('manatype_id')
            ->update();

        $this->table('characters_imbues')->drop()->update();
        $this->table('imbues')->drop()->update();

        // cleanup print queue
        $this->getDeleteBuilder()
            ->delete('lammies')
            ->where(['entity LIKE' => '%Imbue'])
            ->execute();
        $this->getDeleteBuilder()
            ->delete('history')
            ->where(['entity LIKE' => '%Imbue'])
            ->execute();
    }
}
