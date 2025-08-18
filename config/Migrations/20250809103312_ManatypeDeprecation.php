<?php
declare(strict_types=1);

use App\Migrations\Migration;

class ManatypeDeprecation extends Migration
{
    public function up(): void
    {
        $this->table('manatypes')
            ->addColumnBoolean('deprecated', [ 'after' => 'name', 'default' => false])
            ->update();
    }

    public function down(): void
    {
        $this->table('manatypes')->removeColumn('deprecated')->update();
    }
}
