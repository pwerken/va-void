<?php
use Phinx\Migration\AbstractMigration;

class AlterLammiesTable extends AbstractMigration
{
	public function change()
	{
		$table = $this->table('lammies');
		$table
			->addColumn('status', 'string', [
				'default' => 'Queued',
				'limit' => 255,
				'null' => false,
				'after' => 'id',
			])
			->removeColumn('job')
			->removeColumn('printed')
			->update();
	}
}
