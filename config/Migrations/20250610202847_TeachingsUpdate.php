<?php
declare(strict_types=1);

use App\Migrations\Migration;

class TeachingsUpdate extends Migration
{
    public function up(): void
    {
        $this->table('teachings')
            ->dropForeignKey('started_id')
            ->dropForeignKey('updated_id')
            ->removeColumn('started_id')
            ->removeColumn('updated_id')
            ->removeColumn('xp')
            ->update();
    }

    public function down(): void
    {
        $this->table('teachings')
            ->addColumnInteger('started_id', ['after' => 'skill_id', 'null' => true])
            ->addColumnInteger('updated_id', ['after' => 'started_id', 'null' => true])
            ->addColumn('xp', 'decimal', [
                'after' => 'updated_id',
                'default' => '0.0',
                'null' => false,
                'precision' => 3,
                'scale' => 1,
            ])
            ->update();

        $this->table('teachings')
            ->addForeignKey(
                'started_id',
                'events',
                'id',
                ['update' => 'CASCADE', 'delete' => 'RESTRICT'],
            )
            ->addForeignKey(
                'updated_id',
                'events',
                'id',
                ['update' => 'CASCADE', 'delete' => 'RESTRICT'],
            )
            ->update();
    }
}
