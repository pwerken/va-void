<?php
declare(strict_types=1);

use App\Migrations\Migration;

class RemoveSoulpath extends Migration
{
    public function up(): void
    {
        $this->table('characters')
            ->removeColumn('soulpath')
            ->update();
    }

    public function down(): void
    {
        $this->table('characters')
            ->addColumnString('soulpath', ['limit' => 2, 'after' => 'world'])
            ->update();
    }
}
