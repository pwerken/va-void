<?php
declare(strict_types=1);

use App\Migrations\AppMigration;

class Initial extends AppMigration
{
    public function up(): void
    {
        $this->table('attributes')
            ->addColumn('name', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('category', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('code', 'string', [
                'default' => null,
                'limit' => 2,
                'null' => false,
            ])
            ->addIndex(
                [
                    'category',
                    'id',
                ]
            )
            ->create();

        $this->table('attributes_items', ['id' => false, 'primary_key' => ['attribute_id', 'item_id']])
            ->addColumn('attribute_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('item_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addIndex(
                [
                    'attribute_id',
                ]
            )
            ->addIndex(
                [
                    'item_id',
                ]
            )
            ->create();

        $this->table('believes')
            ->addColumn('name', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('modified', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addIndex(
                [
                    'name',
                ]
            )
            ->create();

        $this->table('characters')
            ->addColumn('player_id', 'integer', [
                'comment' => 'PLIN',
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('chin', 'integer', [
                'default' => '1',
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
                'default' => '15.0',
                'null' => false,
                'precision' => 4,
                'scale' => 1,
                'signed' => false,
            ])
            ->addColumn('faction_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('belief_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('group_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('world_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('soulpath', 'string', [
                'default' => null,
                'limit' => 2,
                'null' => false,
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
            ->addColumn('modified', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addIndex(
                [
                    'belief_id',
                ]
            )
            ->addIndex(
                [
                    'faction_id',
                ]
            )
            ->addIndex(
                [
                    'group_id',
                ]
            )
            ->addIndex(
                [
                    'player_id',
                ]
            )
            ->addIndex(
                [
                    'world_id',
                ]
            )
            ->addIndex(
                [
                    'player_id',
                    'chin',
                ]
            )
            ->create();

        $this->table('characters_conditions', ['id' => false, 'primary_key' => ['character_id', 'condition_id']])
            ->addColumn('character_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('condition_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('expiry', 'date', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addIndex(
                [
                    'character_id',
                ]
            )
            ->addIndex(
                [
                    'condition_id',
                ]
            )
            ->create();

        $this->table('characters_powers', ['id' => false, 'primary_key' => ['character_id', 'power_id']])
            ->addColumn('character_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('power_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('expiry', 'date', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addIndex(
                [
                    'character_id',
                ]
            )
            ->addIndex(
                [
                    'power_id',
                ]
            )
            ->create();

        $this->table('characters_skills', ['id' => false, 'primary_key' => ['character_id', 'skill_id']])
            ->addColumn('character_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('skill_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addIndex(
                [
                    'character_id',
                ]
            )
            ->addIndex(
                [
                    'skill_id',
                ]
            )
            ->create();

        $this->table('characters_spells', ['id' => false, 'primary_key' => ['character_id', 'spell_id']])
            ->addColumn('character_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('spell_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('level', 'integer', [
                'default' => '1',
                'limit' => 10,
                'null' => false,
            ])
            ->addIndex(
                [
                    'character_id',
                ]
            )
            ->addIndex(
                [
                    'spell_id',
                ]
            )
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
            ->addColumn('modified', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addIndex(
                [
                    'name',
                ]
            )
            ->create();

        $this->table('groups')
            ->addColumn('name', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('modified', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addIndex(
                [
                    'name',
                ]
            )
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
                'limit' => 11,
                'null' => true,
            ])
            ->addColumn('expiry', 'date', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('modified', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addIndex(
                [
                    'character_id',
                ]
            )
            ->create();

        $this->table('lammies')
            ->addColumn('status', 'string', [
                'default' => 'Queued',
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('entity', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('key1', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('key2', 'integer', [
                'default' => null,
                'limit' => 11,
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
            ->addIndex(
                [
                    'status',
                    'id',
                ]
            )
            ->create();

        $this->table('manatypes')
            ->addColumn('name', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->create();

        $this->table('players')
            ->addColumn('role', 'string', [
                'default' => 'Player',
                'limit' => 255,
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
            ->addColumn('gender', 'string', [
                'default' => null,
                'limit' => 1,
                'null' => true,
            ])
            ->addColumn('date_of_birth', 'date', [
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
                'limit' => 11,
                'null' => true,
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
            ->addColumn('deprecated', 'boolean', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addIndex(
                [
                    'manatype_id',
                ]
            )
            ->addIndex(
                [
                    'sort_order',
                    'name',
                ]
            )
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
            ->addIndex(
                [
                    'name',
                ]
            )
            ->create();

        $this->table('worlds')
            ->addColumn('name', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('modified', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addIndex(
                [
                    'name',
                ]
            )
            ->create();

        $this->table('attributes_items')
            ->addForeignKey(
                'attribute_id',
                'attributes',
                'id',
                [
                    'update' => 'CASCADE',
                    'delete' => 'CASCADE'
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
                    'delete' => 'RESTRICT'
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

    public function down(): void
    {
        $this->table('attributes_items')
            ->dropForeignKey(
                'attribute_id'
            )
            ->dropForeignKey(
                'item_id'
            );

        $this->table('characters')
            ->dropForeignKey(
                'belief_id'
            )
            ->dropForeignKey(
                'faction_id'
            )
            ->dropForeignKey(
                'group_id'
            )
            ->dropForeignKey(
                'player_id'
            )
            ->dropForeignKey(
                'world_id'
            );

        $this->table('characters_conditions')
            ->dropForeignKey(
                'character_id'
            )
            ->dropForeignKey(
                'condition_id'
            );

        $this->table('characters_powers')
            ->dropForeignKey(
                'character_id'
            )
            ->dropForeignKey(
                'power_id'
            );

        $this->table('characters_skills')
            ->dropForeignKey(
                'character_id'
            )
            ->dropForeignKey(
                'skill_id'
            );

        $this->table('characters_spells')
            ->dropForeignKey(
                'character_id'
            )
            ->dropForeignKey(
                'spell_id'
            );

        $this->table('items')
            ->dropForeignKey(
                'character_id'
            );

        $this->table('skills')
            ->dropForeignKey(
                'manatype_id'
            );

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
