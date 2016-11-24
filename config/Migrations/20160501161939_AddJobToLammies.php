<?php
use Phinx\Migration\AbstractMigration;

class AddJobToLammies extends AbstractMigration
{
	public function change()
	{
		$table = $this->table('lammies');
		$table
			->addColumn('job', 'integer', [
				'default' => null,
				'limit' => 10,
				'null' => false,
				'after' => 'key2',
			])
			->update();
	}
}
