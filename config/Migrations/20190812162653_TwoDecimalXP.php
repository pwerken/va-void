<?php
declare(strict_types=1);

use App\Migrations\Migration;

class TwoDecimalXP extends Migration
{
    public function up(): void
    {
        $this->table('characters')
            ->changeColumn('xp', 'decimal', [
                'default' => '15.00',
                'null' => false,
                'precision' => 5,
                'scale' => 2,
                'signed' => false,
            ])
            ->update();
        $this->table('teachings')
            ->changeColumn('xp', 'decimal', [
                'default' => '0.00',
                'null' => false,
                'precision' => 4,
                'scale' => 2,
                'signed' => false,
            ])
            ->update();
    }

    public function down(): void
    {
        $this->table('characters')
            ->changeColumn('xp', 'decimal', [
                'default' => '14.0',
                'null' => false,
                'precision' => 4,
                'scale' => 1,
                'signed' => false,
            ])
            ->update();
        $this->table('teachings')
            ->changeColumn('xp', 'decimal', [
                'default' => '0.0',
                'null' => false,
                'precision' => 3,
                'scale' => 1,
                'signed' => false,
            ])
            ->update();
    }
}
