<?php
use Migrations\AbstractMigration;

class RenameAuditsToHistory extends AbstractMigration
{

	public function up()
	{
		$this->table('audits')->rename('history')->update();
	}

	public function down()
	{
		$this->table('history')->rename('audits')->update();
	}
}
