<?php
declare(strict_types=1);

use App\Migrations\Migration;

class LessPlayerData extends Migration
{
    public function up(): void
    {
        $this->table('players')
            ->removeColumn('gender')
            ->removeColumn('date_of_birth')
            ->update();
    }

    public function down(): void
    {
        $this->table('players')
            ->addColumnString('gender', ['after' => 'email', 'limit' => 1, 'null' => true])
            ->addColumnDate('date_of_birth', ['after' => 'gender'])
            ->update();
    }
}
