<?php
declare(strict_types=1);

use App\Migrations\Migration;

class MoreNotes extends Migration
{
    public function up(): void
    {
        $this->table('characters')
            ->addColumn('referee_notes', 'text', [
                'after' => 'status',
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->renameColumn('comments', 'notes')
            ->update();

        $this->table('items')
            ->addColumn('referee_notes', 'text', [
                'after' => 'player_text',
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->renameColumn('cs_text', 'notes')
            ->update();

        $this->table('conditions')
            ->addColumn('referee_notes', 'text', [
                'after' => 'player_text',
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->renameColumn('cs_text', 'notes')
            ->update();

        $this->table('powers')
            ->addColumn('referee_notes', 'text', [
                'after' => 'player_text',
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->renameColumn('cs_text', 'notes')
            ->update();
    }

    public function down(): void
    {
        $this->table('characters')
            ->removeColumn('referee_notes')
            ->renameColumn('notes', 'comments')
            ->update();

        $this->table('items')
            ->removeColumn('referee_notes')
            ->renameColumn('notes', 'cs_text')
            ->update();

        $this->table('conditions')
            ->removeColumn('referee_notes')
            ->renameColumn('notes', 'cs_text')
            ->update();

        $this->table('powers')
            ->removeColumn('referee_notes')
            ->renameColumn('notes', 'cs_text')
            ->update();
    }
}
