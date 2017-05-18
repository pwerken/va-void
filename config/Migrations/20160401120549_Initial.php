<?php
use Phinx\Migration\AbstractMigration;

class Initial extends AbstractMigration
{

	public function up()
	{
		$this->table('attributes')
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

		$this->table('attributes_items', ['id' => false
				, 'primary_key' => ['attribute_id', 'item_id']])
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
			->addIndex(['attribute_id'])
			->addIndex(['item_id'])
			->create();

		$this->table('believes')
			->addColumn('name', 'string', [
				'default' => null,
				'limit' => 255,
				'null' => false,
			])
			->create();

		$this->table('characters')
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

		$this->table('characters_conditions', ['id' => false
				, 'primary_key' => ['character_id', 'condition_id']])
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
			->addColumn('expiry', 'date', [
				'default' => null,
				'limit' => null,
				'null' => true,
			])
			->addIndex(['character_id'])
			->addIndex(['condition_id'])
			->create();

		$this->table('characters_powers', ['id' => false
				, 'primary_key' => ['character_id', 'power_id']])
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
			->addColumn('expiry', 'date', [
				'default' => null,
				'limit' => null,
				'null' => true,
			])
			->addIndex(['character_id'])
			->addIndex(['power_id'])
			->create();

		$this->table('characters_skills', ['id' => false
				, 'primary_key' => ['character_id', 'skill_id']])
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
			->addIndex(['character_id'])
			->addIndex(['skill_id'])
			->create();

		$this->table('characters_spells', ['id' => false
				, 'primary_key' => ['character_id', 'spell_id']])
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
			->addColumn('level', 'integer', [
				'default' => 1,
				'limit' => 10,
				'null' => false,
			])
			->addIndex(['character_id'])
			->addIndex(['spell_id'])
			->create();

		$this->table('conditions')
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

		$this->table('factions')
			->addColumn('name', 'string', [
				'default' => null,
				'limit' => 255,
				'null' => false,
			])
			->create();

		$this->table('groups')
			->addColumn('name', 'string', [
				'default' => null,
				'limit' => 255,
				'null' => false,
			])
			->create();

		$this->table('items')
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

		$this->table('lammies')
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

		$this->table('manatypes')
			->addColumn('name', 'string', [
				'default' => null,
				'limit' => 255,
				'null' => false,
			])
			->create();

		$this->table('players')
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

		$this->table('powers')
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

		$this->table('skills')
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

		$this->table('spells')
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

		$this->table('worlds')
			->addColumn('name', 'string', [
				'default' => null,
				'limit' => 255,
				'null' => false,
			])
			->create();
	}

	public function down()
	{
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
