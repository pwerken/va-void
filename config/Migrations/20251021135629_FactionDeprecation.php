<?php
declare(strict_types=1);

use App\Migrations\Migration;

class FactionDeprecation extends Migration
{
    public function up(): void
    {
        $this->table('factions')
            ->addColumnBoolean('deprecated', ['after' => 'name'])
            ->update();

        $this->getUpdateBuilder()
            ->update('factions')
            ->set(['deprecated' => true])
            ->where(['id >' => 1, 'id <' => 12])
            ->execute();
    }

    public function down(): void
    {
        $this->table('factions')->removeColumn('deprecated')->update();
    }
}
