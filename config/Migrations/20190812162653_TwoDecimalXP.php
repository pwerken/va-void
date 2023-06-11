<?php
use App\Migrations\AppMigration;

class TwoDecimalXP extends AppMigration
{
	public function up()
	{
		$this->table('characters')
			->changeColumn('xp', 'decimal', [
				'default' => '15.00',
				'null' => false,
				'precision' => 5,
				'scale' => 2,
				'signed' => false,
			])
			->save();
		$this->table('teachings')
			->changeColumn('xp', 'decimal', [
				'default' => '0.00',
				'null' => false,
				'precision' => 4,
				'scale' => 2,
				'signed' => false,
			])
			->save();
	}

	public function down()
	{
		$this->table('characters')
			->changeColumn('xp', 'decimal', [
				'default' => '14.0',
				'null' => false,
				'precision' => 4,
				'scale' => 1,
				'signed' => false,
			])
			->save();
		$this->table('teachings')
			->changeColumn('xp', 'decimal', [
				'default' => '0.0',
				'null' => false,
				'precision' => 3,
				'scale' => 1,
				'signed' => false,
			])
			->save();
	}
}
