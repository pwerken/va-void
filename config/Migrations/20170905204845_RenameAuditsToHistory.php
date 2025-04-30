<?php
declare(strict_types=1);

use App\Migrations\Migration;

class RenameAuditsToHistory extends Migration
{
    public function up(): void
    {
        $this->table('audits')->rename('history')->update();
    }

    public function down(): void
    {
        $this->table('history')->rename('audits')->update();
    }
}
