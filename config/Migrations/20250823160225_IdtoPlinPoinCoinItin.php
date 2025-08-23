<?php
declare(strict_types=1);

use App\Migrations\Migration;

class IdtoPlinPoinCoinItin extends Migration
{
    public function up(): void
    {
        $this->table('players')
            ->renameColumn('id', 'plin')
            ->update();
        $this->table('powers')
            ->renameColumn('id', 'poin')
            ->update();
        $this->table('conditions')
            ->renameColumn('id', 'coin')
            ->update();
        $this->table('items')
            ->renameColumn('id', 'itin')
            ->update();
    }

    public function down(): void
    {
        $this->table('players')
            ->renameColumn('plin', 'id')
            ->update();
        $this->table('powers')
            ->renameColumn('poin', 'id')
            ->update();
        $this->table('conditions')
            ->renameColumn('coin', 'id')
            ->update();
        $this->table('items')
            ->renameColumn('itin', 'id')
            ->update();
    }
}
