<?php
declare(strict_types=1);

use App\Migrations\Migration;

class Events extends Migration
{
    public function up(): void
    {
        $this->table('events')
            ->addColumnString('name')
            ->addColumnDateTime('modified')
            ->addIndex(['name'])
            ->create();
    }

    public function down(): void
    {
        $this->table('events')->drop()->update();
    }
}
