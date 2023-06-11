<?php
use App\Migrations\AppMigration;

class RenameAuditsToHistory extends AppMigration
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
