<?php
use Migrations\AbstractMigration;

class AddJobToLammies extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-change-method
     * @return void
     */
    public function change()
    {
        $table = $this->table('lammies');
        $table->addColumn('job', 'integer', [
            'default' => null,
            'limit' => 10,
            'null' => false,
            'after' => 'key2',
        ]);
        $table->update();
    }
}
