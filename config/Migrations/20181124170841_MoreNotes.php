<?php
use Migrations\AbstractMigration;

class MoreNotes extends AbstractMigration
{

	public function up()
	{

		$this->table('characters')
			->addColumn('referee_notes', 'text', [
				'after' => 'status',
				'default' => null,
				'limit' => null,
				'null' => true,
			])
			->renameColumn('comments', 'notes')
			->update();

		$this->table('items')
			->addColumn('referee_notes', 'text', [
				'after' => 'player_text',
				'default' => null,
				'limit' => null,
				'null' => true,
			])
			->renameColumn('cs_text', 'notes')
			->update();

		$this->table('conditions')
			->addColumn('referee_notes', 'text', [
				'after' => 'player_text',
				'default' => null,
				'limit' => null,
				'null' => true,
			])
			->renameColumn('cs_text', 'notes')
			->update();

		$this->table('powers')
			->addColumn('referee_notes', 'text', [
				'after' => 'player_text',
				'default' => null,
				'limit' => null,
				'null' => true,
			])
			->renameColumn('cs_text', 'notes')
			->update();
	}

    public function down()
    {

		$this->table('characters')
			->removeColumn('referee_notes')
			->renameColumn('notes', 'comments')
			->update();

		$this->table('items')
			->removeColumn('referee_notes')
			->renameColumn('notes', 'cs_text')
			->update();

		$this->table('conditions')
			->removeColumn('referee_notes')
			->renameColumn('notes', 'cs_text')
			->update();

		$this->table('powers')
			->removeColumn('referee_notes')
			->renameColumn('notes', 'cs_text')
			->update();
    }
}
