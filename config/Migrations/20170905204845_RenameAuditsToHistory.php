<?php
declare(strict_types=1);

use App\Migrations\AppMigration;

class RenameAuditsToHistory extends AppMigration
{
	public function up(): void
	{
		$this->table('audits')->rename('history')->update();
	}

	public function down(): void
	{
		$this->table('history')->rename('audits')->update();
	}
}
