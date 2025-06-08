<?php
declare(strict_types=1);

use App\Migrations\Migration;

class SkillBase extends Migration
{
    public function up(): void
    {
        $this->table('skills')
           ->addColumnInteger('base_max', ['after' => 'cost', 'default' => 0])
           ->update();

        // all skills with sort_order < 110 are base skills
        $this->getQueryBuilder('update')
            ->update('skills')
            ->set(['base_max = times_max'])
            ->where(['sort_order <' => 110])
            ->execute();
    }

    public function down(): void
    {
        $this->table('skills')->removeColumn('base_max')->update();
    }
}
