<?php
use Phinx\Migration\AbstractMigration;

class AddSoulpathToCharacters extends AbstractMigration
{
	public function change()
	{
		$table = $this->table('characters');
		$table
			->addColumn('soulpath', 'string', [
				'default' => null,
				'limit' => 2,
				'null' => false,
				'after' => 'world_id'
			])
			->update();
	}
}
