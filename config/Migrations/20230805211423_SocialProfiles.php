<?php
declare(strict_types=1);

use App\Migrations\AppMigration;

class SocialProfiles extends AppMigration
{
    public function up(): void
    {
        $this->table('players')
            ->addColumn('email', 'string', [
                'after' => 'last_name',
                'default' => null,
                'limit' => 191,
                'null' => true,
            ])
            ->addIndex('email', ['unique' => true])
            ->update();

        $this->table('social_profiles')
            ->addColumn('user_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => true,
            ])
            ->addColumn('provider', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('identifier', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('username', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('full_name', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('email', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('created', 'datetime', [
                'default' => null,
                'null' => true,
            ])
            ->addColumn('modified', 'datetime', [
                'default' => null,
                'null' => true,
            ])
            ->addIndex(
                [
                    'user_id',
                ]
            )
            ->create();
    }

    public function down(): void
    {
        $this->table('social_profiles')
            ->drop()
            ->save();

        $this->table('players')
            ->removeIndex('email')
            ->removeColumn('email')
            ->update();
    }
}
