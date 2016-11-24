<?php
use Phinx\Migration\AbstractMigration;

class AlterEnumsToVarchars extends AbstractMigration
{
	public function change()
	{
		$table = $this->table('players');
		$table
			->changeColumn('role', 'string', [
				'default' => 'Player',
				'limit' => 255,
				'null' => false,
			])
			->changeColumn('gender', 'string', [
				'default' => null,
				'limit' => 1,
				'null' => true,
			])
			->update();

		$table = $this->table('attributes');
		$table
			->changeColumn('category', 'string', [
				'default' => null,
				'limit' => 255,
				'null' => true,
			])
			->update();
	}
}
