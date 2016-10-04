<?php
use Migrations\AbstractMigration;

class AddSoulpathToCharacters extends AbstractMigration
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
        $table = $this->table('characters');
        $table->addColumn('soulpath', 'string', [
            'default' => null,
			'limit' => 2,
            'null' => false,
			'after' => 'world_id'
        ]);
        $table->update();
    }
}
