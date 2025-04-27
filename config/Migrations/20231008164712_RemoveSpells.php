<?php
declare(strict_types=1);

use App\Migrations\AppMigration;

class RemoveSpells extends AppMigration
{
    public function up(): void
    {
        // create history entries before dropping the tables
        $history = $this->table('history');

        $rows = $this->getQueryBuilder('select')
            ->select('*')
            ->from('characters_spells')
            ->order(['character_id' => 'ASC', 'spell_id' => 'ASC'])
            ->execute();
        foreach($rows as $row)
        {
            $data = [];
            $data['level'] = $row['level'];

            $current =
                [ 'entity' => 'CharactersSpell'
                , 'key1' => $row['character_id']
                , 'key2' => $row['spell_id']
                , 'data' => json_encode($data)
                , 'modified' => $row['modified']
                , 'modifier_id' => $row['modifier_id']
                ];

            $delete = $current;
            $delete['data'] = null;
            $delete['modified'] = $this->now();
            $delete['modifier_id'] = -2;

            $history
                ->insert($current)
                ->insert($delete)
                ->saveData();
        }
        $rows = $this->getQueryBuilder('select')
            ->select('*')
            ->from('spells')
            ->order(['id' => 'ASC'])
            ->execute();
        foreach($rows as $row)
        {
            $data = [];
            $data['name'] = $row['name'];
            $data['short'] = $row['short'];
            $data['spiritual'] = $row['spiritual'];

            $spell =
                [ 'entity' => 'Spell'
                , 'key1' => $row['id']
                , 'key2' => null
                , 'data' => json_encode($data)
                , 'modified' => $this->now()
                , 'modifier_id' => -2
                ];

            $history
                ->insert($spell)
                ->saveData();
        }

        // now drop the tables
        $this->table('characters_spells')->drop()->save();
        $this->table('spells')->drop()->save();
    }

    public function down(): void
    {
        $spells = $this->table('spells');
        $spells
            ->addColumn('name', 'string',
                [ 'default' => null
                , 'limit' => 255
                , 'null' => false
                ])
            ->addColumn('short', 'string',
                [ 'default' => null
                , 'limit' => 255
                , 'null' => false
                ])
            ->addColumn('spiritual', 'boolean',
                [ 'default' => null
                , 'limit' => null
                , 'null' => false
                ])
            ->addIndex(['name'])
            ->create();

        $charSpells = $this->table('characters_spells',
                [ 'id' => false
                , 'primary_key' => ['character_id', 'spell_id']
                ]);
        $charSpells
            ->addColumn('character_id', 'integer',
                [ 'default' => null
                , 'limit' => 11
                , 'null' => false
                ])
            ->addColumn('spell_id', 'integer',
                [ 'default' => null
                , 'limit' => 11
                , 'null' => false
                ])
            ->addColumn('level', 'integer',
                [ 'default' => null
                , 'limit' => 11
                , 'null' => false
                ])
            ->addColumn('modified', 'datetime',
                [ 'after' => 'level'
                , 'default' => null
                , 'length' => null
                , 'null' => true
                , ])
            ->addColumn('modifier_id', 'integer',
                [ 'after' => 'modified'
                , 'default' => null
                , 'length' => 11
                , 'null' => true
                ])
            ->addIndex(['character_id'])
            ->addIndex(['spell_id'])
            ->addForeignKey('character_id', 'characters', 'id',
                [ 'update' => 'CASCADE'
                , 'delete' => 'RESTRICT'
                ])
            ->addForeignKey('spell_id', 'spells', 'id',
                [ 'update' => 'CASCADE'
                , 'delete' => 'RESTRICT'
                ])
            ->create();
    }
}
