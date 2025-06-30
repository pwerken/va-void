<?php
declare(strict_types=1);

use App\Migrations\Migration;

class RemoveSpells extends Migration
{
    public function up(): void
    {
        // create history entries before dropping the tables
        $history = $this->table('history');

        $rows = $this->getSelectBuilder()
            ->select('*')
            ->from('characters_spells')
            ->order(['character_id' => 'ASC', 'spell_id' => 'ASC'])
            ->execute();
        foreach ($rows as $row) {
            $data = [];
            $data['level'] = $row['level'];

            $current = [
                'entity' => 'CharactersSpell',
                'key1' => $row['character_id'],
                'key2' => $row['spell_id'],
                'data' => json_encode($data),
                'modified' => $row['modified'],
                'modifier_id' => $row['modifier_id'],
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

        $rows = $this->getSelectBuilder()
            ->select('*')
            ->from('spells')
            ->order(['id' => 'ASC'])
            ->execute();
        foreach ($rows as $row) {
            $data = [];
            $data['name'] = $row['name'];
            $data['short'] = $row['short'];
            $data['spiritual'] = $row['spiritual'];

            $spell = [
                'entity' => 'Spell',
                'key1' => $row['id'],
                'key2' => null,
                'data' => json_encode($data),
                'modified' => $this->now(),
                'modifier_id' => -2,
            ];

            $history
                ->insert($spell)
                ->saveData();
        }

        // now drop the tables
        $this->table('characters_spells')->drop()->update();
        $this->table('spells')->drop()->update();
    }

    public function down(): void
    {
        $this->table('spells')
            ->addColumnString('name')
            ->addColumnString('short')
            ->addColumnBoolean('spiritual')
            ->addIndex(['name'])
            ->create();

        $this->relationTable('characters_spells', ['character_id', 'spell_id'])
            ->addColumnInteger('level')
            ->addColumnDateTime('modified')
            ->addColumnInteger('modifier_id', ['null' => true])
            ->addForeignKey(
                'character_id',
                'characters',
                'id',
                [ 'update' => 'CASCADE', 'delete' => 'RESTRICT'],
            )
            ->addForeignKey(
                'spell_id',
                'spells',
                'id',
                [ 'update' => 'CASCADE', 'delete' => 'RESTRICT'],
            )
            ->create();
    }
}
