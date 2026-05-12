<?php
declare(strict_types=1);

use App\Migrations\Migration;

class EventBeginEnd extends Migration
{
    public function up(): void
    {
        $this->table('events')
            ->addColumnDateTime('start', ['after' => 'name'])
            ->addColumnDateTime('end', ['after' => 'start'])
            ->removeColumn('modified')
            ->removeColumn('modifier_id')
            ->save();
    }

    public function down(): void
    {
        $this->table('events')
            ->removeColumn('start')
            ->removeColumn('end')
            ->addColumnDateTime('modified', ['after' => 'name'])
            ->addColumnInteger('modifier_id', ['after' => 'modified', 'null' => true])
            ->save();
    }
}
