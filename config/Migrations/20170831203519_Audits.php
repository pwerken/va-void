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
            ->addColumnInteger('key2', ['null' => true])
            ->addColumnText('data', ['null' => true])
            ->addColumnDateTime('modified')
            ->addColumnInteger('modifier_id', ['null' => true])
            ->create();
    }

    public function down(): void
    {
        $this->table('audits')->drop()->update();
    }
}
