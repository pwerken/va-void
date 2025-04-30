<?php
declare(strict_types=1);

use App\Migrations\Migration;

class Audits extends Migration
{
    public function up(): void
    {
        $this->table('audits')
            ->addColumnString('entity')
            ->addColumnInteger('key1')
            ->addColumnInteger('key2', true)
            ->addColumnText('data', true)
            ->addColumnDateTime('modified')
            ->addColumnInteger('modifier_id', true)
            ->create();
    }

    public function down(): void
    {
        $this->table('audits')->drop()->update();
    }
}
