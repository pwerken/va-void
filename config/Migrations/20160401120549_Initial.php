<?php
use Phinx\Migration\AbstractMigration;

class Initial extends AbstractMigration
{

	public $autoId = false;

	public function up()
	{
		$table = $this->table('attributes');
		$table
			->addColumn('id', 'integer', [
				'default' => null,
				'limit' => 10,
				'null' => false,
				'signed' => false,
			])
			->addPrimaryKey(['id'])
			->addColumn('name', 'string', [
				'default' => null,
				'limit' => 255,
				'null' => true,
			])
			->addColumn('category', 'text', [
				'default' => null,
				'limit' => null,
				'null' => true,
			])
			->addColumn('code', 'string', [
				'default' => null,
				'limit' => 2,
				'null' => false,
			])
			->create();

		$table = $this->table('attributes_items');
		$table
			->addColumn('attribute_id', 'integer', [
				'default' => null,
				'limit' => 10,
				'null' => false,
				'signed' => false,
			])
			->addColumn('item_id', 'integer', [
				'default' => null,
				'limit' => 10,
				'null' => false,
				'signed' => false,
			])
			->addPrimaryKey(['attribute_id', 'item_id'])
			->addIndex(['attribute_id'])
			->addIndex(['item_id'])
			->create();

		$table = $this->table('believes');
		$table
			->addColumn('id', 'integer', [
				'autoIncrement' => true,
				'default' => null,
				'limit' => 10,
				'null' => false,
				'signed' => false,
			])
			->addPrimaryKey(['id'])
			->addColumn('name', 'string', [
				'default' => null,
				'limit' => 255,
				'null' => false,
			])
			->create();

		$table = $this->table('characters');
		$table
			->addColumn('id', 'integer', [
				'autoIncrement' => true,
				'default' => null,
				'limit' => 10,
				'null' => false,
				'signed' => false,
			])
			->addPrimaryKey(['id'])
			->addColumn('player_id', 'integer', [
				'comment' => 'PLIN',
				'default' => null,
				'limit' => 10,
				'null' => false,
				'signed' => false,
			])
			->addColumn('chin', 'integer', [
				'default' => 1,
				'limit' => 2,
				'null' => false,
				'signed' => false,
			])
			->addColumn('name', 'string', [
				'default' => null,
				'limit' => 255,
				'null' => false,
			])
			->addColumn('xp', 'decimal', [
				'default' => 15,
				'null' => false,
				'precision' => 4,
				'scale' => 1,
				'signed' => false,
			])
			->addColumn('faction_id', 'integer', [
				'default' => null,
				'limit' => 10,
				'null' => false,
				'signed' => false,
			])
			->addColumn('belief_id', 'integer', [
				'default' => null,
				'limit' => 10,
				'null' => false,
				'signed' => false,
			])
			->addColumn('group_id', 'integer', [
				'default' => null,
				'limit' => 10,
				'null' => false,
				'signed' => false,
			])
			->addColumn('world_id', 'integer', [
				'default' => null,
				'limit' => 10,
				'null' => false,
				'signed' => false,
			])
			->addColumn('status', 'string', [
				'default' => null,
				'limit' => 255,
				'null' => true,
			])
			->addColumn('comments', 'text', [
				'default' => null,
				'limit' => null,
				'null' => true,
			])
			->addColumn('created', 'datetime', [
				'default' => null,
				'limit' => null,
				'null' => true,
			])
			->addColumn('modified', 'datetime', [
				'default' => null,
				'limit' => null,
				'null' => true,
			])
			->addIndex(['belief_id'])
			->addIndex(['faction_id'])
			->addIndex(['group_id'])
			->addIndex(['player_id'])
			->addIndex(['world_id'])
			->addIndex(['player_id', 'chin'])
			->create();

		$table = $this->table('characters_conditions');
		$table
			->addColumn('character_id', 'integer', [
				'default' => null,
				'limit' => 10,
				'null' => false,
				'signed' => false,
			])
			->addColumn('condition_id', 'integer', [
				'default' => null,
				'limit' => 10,
				'null' => false,
				'signed' => false,
			])
			->addPrimaryKey(['character_id', 'condition_id'])
			->addColumn('expiry', 'date', [
				'default' => null,
				'limit' => null,
				'null' => true,
			])
			->addIndex(['character_id'])
			->addIndex(['condition_id'])
			->create();

		$table = $this->table('characters_powers');
		$table
			->addColumn('character_id', 'integer', [
				'default' => null,
				'limit' => 10,
				'null' => false,
				'signed' => false,
			])
			->addColumn('power_id', 'integer', [
				'default' => null,
				'limit' => 10,
				'null' => false,
				'signed' => false,
			])
			->addPrimaryKey(['character_id', 'power_id'])
			->addColumn('expiry', 'date', [
				'default' => null,
				'limit' => null,
				'null' => true,
			])
			->addIndex(['character_id'])
			->addIndex(['power_id'])
			->create();

		$table = $this->table('characters_skills');
		$table
			->addColumn('character_id', 'integer', [
				'default' => null,
				'limit' => 10,
				'null' => false,
				'signed' => false,
			])
			->addColumn('skill_id', 'integer', [
				'default' => null,
				'limit' => 10,
				'null' => false,
				'signed' => false,
			])
			->addPrimaryKey(['character_id', 'skill_id'])
			->addIndex(['character_id'])
			->addIndex(['skill_id'])
			->create();

		$table = $this->table('characters_spells');
		$table
			->addColumn('character_id', 'integer', [
				'default' => null,
				'limit' => 10,
				'null' => false,
				'signed' => false,
			])
			->addColumn('spell_id', 'integer', [
				'default' => null,
				'limit' => 10,
				'null' => false,
				'signed' => false,
			])
			->addPrimaryKey(['character_id', 'spell_id'])
			->addColumn('level', 'integer', [
				'default' => 1,
				'limit' => 10,
				'null' => false,
			])
			->addIndex(['character_id'])
			->addIndex(['spell_id'])
			->create();

		$table = $this->table('conditions');
		$table
			->addColumn('id', 'integer', [
				'autoIncrement' => true,
				'default' => null,
				'limit' => 10,
				'null' => false,
				'signed' => false,
			])
			->addPrimaryKey(['id'])
			->addColumn('name', 'string', [
				'default' => null,
				'limit' => 255,
				'null' => false,
			])
			->addColumn('player_text', 'text', [
				'default' => null,
				'limit' => null,
				'null' => false,
			])
			->addColumn('cs_text', 'text', [
				'default' => null,
				'limit' => null,
				'null' => true,
			])
			->addColumn('created', 'datetime', [
				'default' => null,
				'limit' => null,
				'null' => true,
			])
			->addColumn('modified', 'datetime', [
				'default' => null,
				'limit' => null,
				'null' => true,
			])
			->create();

		$table = $this->table('factions');
		$table
			->addColumn('id', 'integer', [
				'autoIncrement' => true,
				'default' => null,
				'limit' => 10,
				'null' => false,
				'signed' => false,
			])
			->addPrimaryKey(['id'])
			->addColumn('name', 'string', [
				'default' => null,
				'limit' => 255,
				'null' => false,
			])
			->create();

		$table = $this->table('groups');
		$table
			->addColumn('id', 'integer', [
				'autoIncrement' => true,
				'default' => null,
				'limit' => 10,
				'null' => false,
				'signed' => false,
			])
			->addPrimaryKey(['id'])
			->addColumn('name', 'string', [
				'default' => null,
				'limit' => 255,
				'null' => false,
			])
			->create();

		$table = $this->table('items');
		$table
			->addColumn('id', 'integer', [
				'comment' => 'ITIN',
				'default' => null,
				'limit' => 10,
				'null' => false,
				'signed' => false,
			])
			->addPrimaryKey(['id'])
			->addColumn('name', 'string', [
				'default' => null,
				'limit' => 255,
				'null' => false,
			])
			->addColumn('description', 'string', [
				'default' => null,
				'limit' => 255,
				'null' => true,
			])
			->addColumn('player_text', 'text', [
				'default' => null,
				'limit' => null,
				'null' => true,
			])
			->addColumn('cs_text', 'text', [
				'default' => null,
				'limit' => null,
				'null' => true,
			])
			->addColumn('character_id', 'integer', [
				'default' => null,
				'limit' => 10,
				'null' => true,
				'signed' => false,
			])
			->addColumn('expiry', 'date', [
				'default' => null,
				'limit' => null,
				'null' => true,
			])
			->addColumn('created', 'datetime', [
				'default' => null,
				'limit' => null,
				'null' => true,
			])
			->addColumn('modified', 'datetime', [
				'default' => null,
				'limit' => null,
				'null' => true,
			])
			->addIndex(['character_id'])
			->create();

		$table = $this->table('lammies');
		$table
			->addColumn('id', 'integer', [
				'autoIncrement' => true,
				'default' => null,
				'limit' => 10,
				'null' => false,
				'signed' => false,
			])
			->addPrimaryKey(['id'])
			->addColumn('entity', 'string', [
				'default' => null,
				'limit' => 255,
				'null' => false,
			])
			->addColumn('key1', 'integer', [
				'default' => null,
				'limit' => 10,
				'null' => false,
			])
			->addColumn('key2', 'integer', [
				'default' => null,
				'limit' => 10,
				'null' => true,
			])
			->addColumn('printed', 'boolean', [
				'default' => null,
				'limit' => null,
				'null' => false,
			])
			->addColumn('created', 'datetime', [
				'default' => null,
				'limit' => null,
				'null' => true,
			])
			->addColumn('modified', 'datetime', [
				'default' => null,
				'limit' => null,
				'null' => true,
			])
			->create();

		$table = $this->table('manatypes');
		$table
			->addColumn('id', 'integer', [
				'default' => null,
				'limit' => 10,
				'null' => false,
				'signed' => false,
			])
			->addPrimaryKey(['id'])
			->addColumn('name', 'string', [
				'default' => null,
				'limit' => 255,
				'null' => false,
			])
			->create();

		$table = $this->table('players');
		$table
			->addColumn('id', 'integer', [
				'comment' => 'PLIN',
				'default' => null,
				'limit' => 10,
				'null' => false,
				'signed' => false,
			])
			->addPrimaryKey(['id'])
			->addColumn('role', 'text', [
				'comment' => 'authorisation information',
				'default' => null,
				'limit' => null,
				'null' => false,
			])
			->addColumn('password', 'string', [
				'default' => null,
				'limit' => 255,
				'null' => true,
			])
			->addColumn('first_name', 'string', [
				'default' => null,
				'limit' => 255,
				'null' => true,
			])
			->addColumn('insertion', 'string', [
				'default' => null,
				'limit' => 255,
				'null' => true,
			])
			->addColumn('last_name', 'string', [
				'default' => null,
				'limit' => 255,
				'null' => true,
			])
			->addColumn('gender', 'text', [
				'default' => null,
				'limit' => null,
				'null' => true,
			])
			->addColumn('date_of_birth', 'date', [
				'default' => null,
				'limit' => null,
				'null' => true,
			])
			->addColumn('created', 'datetime', [
				'default' => null,
				'limit' => null,
				'null' => true,
			])
			->addColumn('modified', 'datetime', [
				'default' => null,
				'limit' => null,
				'null' => true,
			])
			->create();

		$table = $this->table('powers');
		$table
			->addColumn('id', 'integer', [
				'autoIncrement' => true,
				'default' => null,
				'limit' => 10,
				'null' => false,
				'signed' => false,
			])
			->addPrimaryKey(['id'])
			->addColumn('name', 'string', [
				'default' => null,
				'limit' => 255,
				'null' => false,
			])
			->addColumn('player_text', 'text', [
				'default' => null,
				'limit' => null,
				'null' => false,
			])
			->addColumn('cs_text', 'text', [
				'default' => null,
				'limit' => null,
				'null' => true,
			])
			->addColumn('created', 'datetime', [
				'default' => null,
				'limit' => null,
				'null' => true,
			])
			->addColumn('modified', 'datetime', [
				'default' => null,
				'limit' => null,
				'null' => true,
			])
			->create();

		$table = $this->table('skills');
		$table
			->addColumn('id', 'integer', [
				'autoIncrement' => true,
				'default' => null,
				'limit' => 10,
				'null' => false,
				'signed' => false,
			])
			->addPrimaryKey(['id'])
			->addColumn('name', 'string', [
				'default' => null,
				'limit' => 255,
				'null' => false,
			])
			->addColumn('cost', 'integer', [
				'default' => null,
				'limit' => 10,
				'null' => false,
			])
			->addColumn('manatype_id', 'integer', [
				'default' => null,
				'limit' => 10,
				'null' => true,
				'signed' => false,
			])
			->addColumn('mana_amount', 'integer', [
				'default' => null,
				'limit' => 10,
				'null' => true,
			])
			->addColumn('sort_order', 'integer', [
				'default' => null,
				'limit' => 10,
				'null' => true,
			])
			->addIndex(['manatype_id'])
			->create();

		$table = $this->table('spells');
		$table
			->addColumn('id', 'integer', [
				'autoIncrement' => true,
				'default' => null,
				'limit' => 10,
				'null' => false,
				'signed' => false,
			])
			->addPrimaryKey(['id'])
			->addColumn('name', 'string', [
				'default' => null,
				'limit' => 255,
				'null' => false,
			])
			->addColumn('short', 'string', [
				'default' => null,
				'limit' => 255,
				'null' => false,
			])
			->addColumn('spiritual', 'boolean', [
				'default' => null,
				'limit' => null,
				'null' => false,
			])
			->create();

		$table = $this->table('worlds');
		$table
			->addColumn('id', 'integer', [
				'autoIncrement' => true,
				'default' => null,
				'limit' => 10,
				'null' => false,
				'signed' => false,
			])
			->addPrimaryKey(['id'])
			->addColumn('name', 'string', [
				'default' => null,
				'limit' => 255,
				'null' => false,
			])
			->create();

		$this->table('attributes_items')
			->addForeignKey(
				'attribute_id',
				'attributes',
				'id',
				[
					'update' => 'CASCADE',
					'delete' => 'RESTRICT'
				]
			)
			->addForeignKey(
				'item_id',
				'items',
				'id',
				[
					'update' => 'CASCADE',
					'delete' => 'RESTRICT'
				]
			)
			->update();

		$this->table('characters')
			->addForeignKey(
				'belief_id',
				'believes',
				'id',
				[
					'update' => 'CASCADE',
					'delete' => 'RESTRICT'
				]
			)
			->addForeignKey(
				'faction_id',
				'factions',
				'id',
				[
					'update' => 'CASCADE',
					'delete' => 'RESTRICT'
				]
			)
			->addForeignKey(
				'group_id',
				'groups',
				'id',
				[
					'update' => 'CASCADE',
					'delete' => 'RESTRICT'
				]
			)
			->addForeignKey(
				'player_id',
				'players',
				'id',
				[
					'update' => 'CASCADE',
					'delete' => 'RESTRICT'
				]
			)
			->addForeignKey(
				'world_id',
				'worlds',
				'id',
				[
					'update' => 'CASCADE',
					'delete' => 'RESTRICT'
				]
			)
			->update();

		$this->table('characters_conditions')
			->addForeignKey(
				'character_id',
				'characters',
				'id',
				[
					'update' => 'CASCADE',
					'delete' => 'RESTRICT'
				]
			)
			->addForeignKey(
				'condition_id',
				'conditions',
				'id',
				[
					'update' => 'CASCADE',
					'delete' => 'RESTRICT'
				]
			)
			->update();

		$this->table('characters_powers')
			->addForeignKey(
				'character_id',
				'characters',
				'id',
				[
					'update' => 'CASCADE',
					'delete' => 'RESTRICT'
				]
			)
			->addForeignKey(
				'power_id',
				'powers',
				'id',
				[
					'update' => 'CASCADE',
					'delete' => 'RESTRICT'
				]
			)
			->update();

		$this->table('characters_skills')
			->addForeignKey(
				'character_id',
				'characters',
				'id',
				[
					'update' => 'CASCADE',
					'delete' => 'RESTRICT'
				]
			)
			->addForeignKey(
				'skill_id',
				'skills',
				'id',
				[
					'update' => 'CASCADE',
					'delete' => 'RESTRICT'
				]
			)
			->update();

		$this->table('characters_spells')
			->addForeignKey(
				'character_id',
				'characters',
				'id',
				[
					'update' => 'CASCADE',
					'delete' => 'CASCADE'
				]
			)
			->addForeignKey(
				'spell_id',
				'spells',
				'id',
				[
					'update' => 'CASCADE',
					'delete' => 'RESTRICT'
				]
			)
			->update();

		$this->table('items')
			->addForeignKey(
				'character_id',
				'characters',
				'id',
				[
					'update' => 'CASCADE',
					'delete' => 'RESTRICT'
				]
			)
			->update();

		$this->table('skills')
			->addForeignKey(
				'manatype_id',
				'manatypes',
				'id',
				[
					'update' => 'CASCADE',
					'delete' => 'RESTRICT'
				]
			)
			->update();

	}

	public function down()
	{
		$this->table('attributes_items')
			->dropForeignKey('attribute_id')
			->dropForeignKey('item_id');

		$this->table('characters')
			->dropForeignKey('belief_id')
			->dropForeignKey('faction_id')
			->dropForeignKey('group_id')
			->dropForeignKey('player_id')
			->dropForeignKey('world_id');

		$this->table('characters_conditions')
			->dropForeignKey('character_id')
			->dropForeignKey('condition_id');

		$this->table('characters_powers')
			->dropForeignKey('character_id')
			->dropForeignKey('power_id');

		$this->table('characters_skills')
			->dropForeignKey('character_id')
			->dropForeignKey('skill_id');

		$this->table('characters_spells')
			->dropForeignKey('character_id')
			->dropForeignKey('spell_id');

		$this->table('items')
			->dropForeignKey('character_id');

		$this->table('skills')
			->dropForeignKey('manatype_id');

		$this->dropTable('attributes');
		$this->dropTable('attributes_items');
		$this->dropTable('believes');
		$this->dropTable('characters');
		$this->dropTable('characters_conditions');
		$this->dropTable('characters_powers');
		$this->dropTable('characters_skills');
		$this->dropTable('characters_spells');
		$this->dropTable('conditions');
		$this->dropTable('factions');
		$this->dropTable('groups');
		$this->dropTable('items');
		$this->dropTable('lammies');
		$this->dropTable('manatypes');
		$this->dropTable('players');
		$this->dropTable('powers');
		$this->dropTable('skills');
		$this->dropTable('spells');
		$this->dropTable('worlds');
	}
}
