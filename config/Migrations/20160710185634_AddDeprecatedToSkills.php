<?php
use Phinx\Migration\AbstractMigration;

class AddDeprecatedToSkills extends AbstractMigration
{
	public function change()
	{
		$table = $this->table('skills');
		$table
			->addColumn('deprecated', 'boolean', [
				'default' => null,
				'null' => false,
			])
			->update();
	}
}
