<?php
declare(strict_types=1);

use App\Migrations\Migration;

class SocialProfiles extends Migration
{
    public function up(): void
    {
        $this->table('players')
            ->addColumnString('email', [
                'after' => 'last_name',
                'limit' => 191,
                'null' => true,
            ])
            ->addIndex('email', ['unique' => true])
            ->update();

        $this->table('social_profiles')
            ->addColumnInteger('user_id', ['null' => true])
            ->addColumnString('provider')
            ->addColumnString('identifier')
            ->addColumnString('username', ['null' => true])
            ->addColumnString('full_name', ['null' => true])
            ->addColumnString('email', ['null' => true])
            ->addColumnDateTime('created')
            ->addColumnDateTime('modified')
            ->addIndex(['user_id'])
            ->create();
    }

    public function down(): void
    {
        $this->table('social_profiles')->drop()->update();

        $this->table('players')
            ->removeIndex('email')
            ->removeColumn('email')
            ->update();
    }
}
