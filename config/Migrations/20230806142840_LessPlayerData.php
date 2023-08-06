<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class LessPlayerData extends AbstractMigration
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
            ->addColumn('gender', 'string', [
                'after' => 'email',
                'default' => null,
                'limit' => 1,
                'null' => true,
            ])
            ->addColumn('date_of_birth', 'date', [
                'after' => 'gender',
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->update();
    }
}
