<?php
declare(strict_types=1);

use App\Migrations\Migration;

class ItemImbueCap extends Migration
{
    public function up(): void
    {
        $this->table('items')
            ->addColumnInteger('imbue_capacity', ['after' => 'notes', 'null' => true])
            ->update();
    }

    public function down(): void
    {
        $this->table('items')
            ->removeColumn('imbue_capacity')
            ->update();
    }
}
