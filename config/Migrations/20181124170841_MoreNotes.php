<?php
declare(strict_types=1);

use App\Migrations\Migration;

class MoreNotes extends Migration
{
    public function up(): void
    {
        $this->table('characters')
            ->addColumnText('referee_notes', ['after' => 'status', 'null' => true])
            ->renameColumn('comments', 'notes')
            ->update();

        $this->table('items')
            ->addColumnText('referee_notes', ['after' => 'player_text', 'null' => true])
            ->renameColumn('cs_text', 'notes')
            ->update();

        $this->table('conditions')
            ->addColumnText('referee_notes', ['after' => 'player_text', 'null' => true])
            ->renameColumn('cs_text', 'notes')
            ->update();

        $this->table('powers')
            ->addColumnText('referee_notes', ['after' => 'player_text', 'null' => true])
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
