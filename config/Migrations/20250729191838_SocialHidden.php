<?php
declare(strict_types=1);

use App\Migrations\Migration;

class SocialHidden extends Migration
{
    public function up(): void
    {
        $this->table('social_profiles')
            ->addColumnBoolean('hidden', [ 'after' => 'user_id', 'default' => false])
            ->addIndex(['provider', 'identifier'], ['unique' => true])
            ->update();
    }

    public function down(): void
    {
        $this->table('social_profiles')
            ->removeIndex(['provider', 'identifier'])
            ->removeColumn('hidden')
            ->update();
    }
}
