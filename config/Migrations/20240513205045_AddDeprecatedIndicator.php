<?php
declare(strict_types=1);

use App\Migrations\Migration;

class AddDeprecatedIndicator extends Migration
{
    public function up(): void
    {
        $this->table('conditions')
            ->addColumnBoolean('deprecated', [ 'after' => 'notes', 'default' => false])
            ->update();

        $this->table('powers')
            ->addColumnBoolean('deprecated', [ 'after' => 'notes', 'default' => false])
            ->update();

        $this->table('items')
            ->addColumnBoolean('deprecated', [ 'after' => 'expiry', 'default' => false])
            ->update();
    }

    public function down(): void
    {
        $this->table('conditions')->removeColumn('deprecated')->update();
        $this->table('powers')->removeColumn('deprecated')->update();
        $this->table('items')->removeColumn('deprecated')->update();
    }
}
