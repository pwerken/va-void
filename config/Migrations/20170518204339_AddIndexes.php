<?php
use Phinx\Migration\AbstractMigration;

class AddIndexes extends AbstractMigration
{
	public function change()
	{
		$this->table('attributes')
			->addIndex(['category', 'id'])
			->update();
		$this->table('believes')
			->addIndex(['name'])
			->update();
		$this->table('factions')
			->addIndex(['name'])
			->update();
		$this->table('groups')
			->addIndex(['name'])
			->update();
		$this->table('lammies')
			->addIndex(['status', 'id'])
			->update();
		$this->table('skills')
			->addIndex(['sort_order', 'name'])
			->update();
		$this->table('spells')
			->addIndex(['name'])
			->update();
		$this->table('worlds')
			->addIndex(['name'])
			->update();
	}
}
