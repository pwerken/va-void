<?php
declare(strict_types=1);

use App\Migrations\Migration;

class Initial extends Migration
{
    public function up(): void
    {
        $this->table('attributes')
            ->addColumnString('name', ['null' => true])
            ->addColumnString('category', ['null' => true])
            ->addColumnString('code', ['limit' => 2])
            ->addIndex(['category', 'id' ])
            ->create();

        $this->relationTable('attributes_items', ['attribute_id', 'item_id'])
            ->create();

        $this->table('believes')
            ->addColumnString('name')
            ->addColumnDateTime('modified')
            ->addIndex(['name'])
            ->create();

        $this->table('characters')
            ->addColumnInteger('player_id')
            ->addColumnInteger('chin', ['default' => '1', 'limit' => 2])
            ->addColumnString('name')
            ->addColumn('xp', 'decimal', [
                'default' => '15.0',
                'null' => false,
                'precision' => 4,
                'scale' => 1,
                'signed' => false,
            ])
            ->addColumnInteger('faction_id')
            ->addColumnInteger('belief_id')
            ->addColumnInteger('group_id')
            ->addColumnInteger('world_id')
            ->addColumnString('soulpath', ['limit' => 2])
            ->addColumnString('status')
            ->addColumnText('comments', ['null' => true])
            ->addColumnDateTime('modified')
            ->addIndex(['player_id'])
            ->addIndex(['player_id', 'chin'])
            ->addIndex(['belief_id'])
            ->addIndex(['faction_id'])
            ->addIndex(['group_id'])
            ->addIndex(['world_id'])
            ->create();

        $this->relationTable('characters_conditions', ['character_id', 'condition_id'])
            ->addColumnDate('expiry')
            ->create();

        $this->relationTable('characters_powers', ['character_id', 'power_id'])
            ->addColumnDate('expiry')
            ->create();

        $this->relationTable('characters_skills', ['character_id', 'skill_id'])
            ->create();

        $this->relationTable('characters_spells', ['character_id', 'spell_id'])
            ->addColumnInteger('level')
            ->create();

        $this->table('conditions')
            ->addColumnString('name')
            ->addColumnText('player_text')
            ->addColumnText('cs_text', ['null' => true])
            ->addColumnDateTime('modified')
            ->create();

        $this->table('factions')
            ->addColumnString('name')
            ->addColumnDateTime('modified')
            ->addIndex(['name'])
            ->create();

        $this->table('groups')
            ->addColumnString('name')
            ->addColumnDateTime('modified')
            ->addIndex(['name'])
            ->create();

        $this->table('items')
            ->addColumnString('name')
            ->addColumnString('description', ['null' => true])
            ->addColumnText('player_text', ['null' => true])
            ->addColumnText('cs_text', ['null' => true])
            ->addColumnInteger('character_id', ['null' => true])
            ->addColumnDate('expiry')
            ->addColumnDateTime('modified')
            ->addIndex(['character_id'])
            ->create();

        $this->table('lammies')
            ->addColumnString('status')
            ->addColumnString('entity')
            ->addColumnInteger('key1')
            ->addColumnInteger('key2', ['null' => true])
            ->addColumnDateTime('created')
            ->addColumnDateTime('modified')
            ->addIndex(['status', 'id'])
            ->create();

        $this->table('manatypes')
            ->addColumnString('name')
            ->create();

        $this->table('players')
            ->addColumnString('role')
            ->addColumnString('password', ['null' => true])
            ->addColumnString('first_name', ['null' => true])
            ->addColumnString('insertion', ['null' => true])
            ->addColumnString('last_name', ['null' => true])
            ->addColumnString('gender', ['limit' => 1, 'null' => true])
            ->addColumnDate('date_of_birth')
            ->addColumnDateTime('modified')
            ->create();

        $this->table('powers')
            ->addColumnString('name')
            ->addColumnText('player_text')
            ->addColumnText('cs_text', ['null' => true])
            ->addColumnDateTime('modified')
            ->create();

        $this->table('skills')
            ->addColumnString('name')
            ->addColumnInteger('cost')
            ->addColumnInteger('manatype_id', ['null' => true])
            ->addColumnInteger('mana_amount', ['null' => true])
            ->addColumnInteger('sort_order', ['null' => true])
            ->addColumnBoolean('deprecated')
            ->addIndex(['manatype_id'])
            ->addIndex(['sort_order', 'name'])
            ->create();

        $this->table('spells')
            ->addColumnString('name')
            ->addColumnString('short')
            ->addColumnBoolean('spiritual')
            ->addIndex(['name'])
            ->create();

        $this->table('worlds')
            ->addColumnString('name')
            ->addColumnDateTime('modified')
            ->addIndex(['name'])
            ->create();

        $this->table('attributes_items')
            ->addForeignKey(
                'attribute_id',
                'attributes',
                'id',
                ['update' => 'CASCADE', 'delete' => 'CASCADE'],
            )
            ->addForeignKey(
                'item_id',
                'items',
                'id',
                ['update' => 'CASCADE', 'delete' => 'RESTRICT'],
            )
            ->update();

        $this->table('characters')
            ->addForeignKey(
                'belief_id',
                'believes',
                'id',
                ['update' => 'CASCADE', 'delete' => 'RESTRICT'],
            )
            ->addForeignKey(
                'faction_id',
                'factions',
                'id',
                ['update' => 'CASCADE', 'delete' => 'RESTRICT'],
            )
            ->addForeignKey(
                'group_id',
                'groups',
                'id',
                ['update' => 'CASCADE', 'delete' => 'RESTRICT'],
            )
            ->addForeignKey(
                'player_id',
                'players',
                'id',
                ['update' => 'CASCADE', 'delete' => 'RESTRICT'],
            )
            ->addForeignKey(
                'world_id',
                'worlds',
                'id',
                ['update' => 'CASCADE', 'delete' => 'RESTRICT'],
            )
            ->update();

        $this->table('characters_conditions')
            ->addForeignKey(
                'character_id',
                'characters',
                'id',
                ['update' => 'CASCADE', 'delete' => 'RESTRICT'],
            )
            ->addForeignKey(
                'condition_id',
                'conditions',
                'id',
                ['update' => 'CASCADE', 'delete' => 'RESTRICT'],
            )
            ->update();

        $this->table('characters_powers')
            ->addForeignKey(
                'character_id',
                'characters',
                'id',
                ['update' => 'CASCADE', 'delete' => 'RESTRICT'],
            )
            ->addForeignKey(
                'power_id',
                'powers',
                'id',
                ['update' => 'CASCADE', 'delete' => 'RESTRICT'],
            )
            ->update();

        $this->table('characters_skills')
            ->addForeignKey(
                'character_id',
                'characters',
                'id',
                ['update' => 'CASCADE', 'delete' => 'RESTRICT'],
            )
            ->addForeignKey(
                'skill_id',
                'skills',
                'id',
                ['update' => 'CASCADE', 'delete' => 'RESTRICT'],
            )
            ->update();

        $this->table('characters_spells')
            ->addForeignKey(
                'character_id',
                'characters',
                'id',
                ['update' => 'CASCADE', 'delete' => 'RESTRICT'],
            )
            ->addForeignKey(
                'spell_id',
                'spells',
                'id',
                ['update' => 'CASCADE', 'delete' => 'RESTRICT'],
            )
            ->update();

        $this->table('items')
            ->addForeignKey(
                'character_id',
                'characters',
                'id',
                ['update' => 'CASCADE', 'delete' => 'RESTRICT'],
            )
            ->update();

        $this->table('skills')
            ->addForeignKey(
                'manatype_id',
                'manatypes',
                'id',
                ['update' => 'CASCADE', 'delete' => 'RESTRICT'],
            )
            ->update();
    }

    public function down(): void
    {
        $this->table('attributes_items')
            ->dropForeignKey('attribute_id')
            ->dropForeignKey('item_id')
            ->update();
        $this->table('characters')
            ->dropForeignKey('belief_id')
            ->dropForeignKey('faction_id')
            ->dropForeignKey('group_id')
            ->dropForeignKey('player_id')
            ->dropForeignKey('world_id')
            ->update();
        $this->table('characters_conditions')
            ->dropForeignKey('character_id')
            ->dropForeignKey('condition_id')
            ->update();
        $this->table('characters_powers')
            ->dropForeignKey('character_id')
            ->dropForeignKey('power_id')
            ->update();
        $this->table('characters_skills')
            ->dropForeignKey('character_id')
            ->dropForeignKey('skill_id')
            ->update();
        $this->table('characters_spells')
            ->dropForeignKey('character_id')
            ->dropForeignKey('spell_id')
            ->update();
        $this->table('items')
            ->dropForeignKey('character_id')
            ->update();
        $this->table('skills')
            ->dropForeignKey('manatype_id')
            ->update();

        $this->table('attributes')->drop()->update();
        $this->table('attributes_items')->drop()->update();
        $this->table('believes')->drop()->update();
        $this->table('characters')->drop()->update();
        $this->table('characters_conditions')->drop()->update();
        $this->table('characters_powers')->drop()->update();
        $this->table('characters_skills')->drop()->update();
        $this->table('characters_spells')->drop()->update();
        $this->table('conditions')->drop()->update();
        $this->table('factions')->drop()->update();
        $this->table('groups')->drop()->update();
        $this->table('items')->drop()->update();
        $this->table('lammies')->drop()->update();
        $this->table('manatypes')->drop()->update();
        $this->table('players')->drop()->update();
        $this->table('powers')->drop()->update();
        $this->table('skills')->drop()->update();
        $this->table('spells')->drop()->update();
        $this->table('worlds')->drop()->update();
    }
}
