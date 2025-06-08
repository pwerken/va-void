<?php
declare(strict_types=1);

use App\Migrations\Migration;

class CreatorModifier extends Migration
{
    public function up(): void
    {
        $this->tableAddModifiedColumnAfter('attributes_items', 'item_id');
        $this->tableAddModifiedColumnAfter('characters_conditions', 'expiry');
        $this->tableAddModifiedColumnAfter('characters_powers', 'expiry');
        $this->tableAddModifiedColumnAfter('characters_skills', 'skill_id');
        $this->tableAddModifiedColumnAfter('characters_spells', 'level');

        $this->tableAddModifierColumnAfter('attributes_items');
        $this->tableAddModifierColumnAfter('believes');
        $this->tableAddModifierColumnAfter('characters');
        $this->tableAddModifierColumnAfter('characters_conditions');
        $this->tableAddModifierColumnAfter('characters_powers');
        $this->tableAddModifierColumnAfter('characters_skills');
        $this->tableAddModifierColumnAfter('characters_spells');
        $this->tableAddModifierColumnAfter('conditions');
        $this->tableAddModifierColumnAfter('events');
        $this->tableAddModifierColumnAfter('factions');
        $this->tableAddModifierColumnAfter('groups');
        $this->tableAddModifierColumnAfter('items');
        $this->tableAddModifierColumnAfter('players');
        $this->tableAddModifierColumnAfter('powers');
        $this->tableAddModifierColumnAfter('teachings');
        $this->tableAddModifierColumnAfter('worlds');

        $this->table('lammies')
            ->addColumnInteger('creator_id', ['after' => 'created', 'null' => true])
            ->update();
    }

    public function down(): void
    {
        $this->table('attributes_items')->removeColumn('modified')->update();
        $this->table('characters_conditions')->removeColumn('modified')->update();
        $this->table('characters_powers')->removeColumn('modified')->update();
        $this->table('characters_skills')->removeColumn('modified')->update();
        $this->table('characters_spells')->removeColumn('modified')->update();

        $this->table('attributes_items')->removeColumn('modifier_id')->update();
        $this->table('believes')->removeColumn('modifier_id')->update();
        $this->table('characters')->removeColumn('modifier_id')->update();
        $this->table('characters_conditions')->removeColumn('modifier_id')->update();
        $this->table('characters_powers')->removeColumn('modifier_id')->update();
        $this->table('characters_skills')->removeColumn('modifier_id')->update();
        $this->table('characters_spells')->removeColumn('modifier_id')->update();
        $this->table('conditions')->removeColumn('modifier_id')->update();
        $this->table('events')->removeColumn('modifier_id')->update();
        $this->table('factions')->removeColumn('modifier_id')->update();
        $this->table('groups')->removeColumn('modifier_id')->update();
        $this->table('items')->removeColumn('modifier_id')->update();
        $this->table('players')->removeColumn('modifier_id')->update();
        $this->table('powers')->removeColumn('modifier_id') ->update();
        $this->table('teachings')->removeColumn('modifier_id')->update();
        $this->table('worlds')->removeColumn('modifier_id')->update();

        $this->table('lammies')->removeColumn('creator_id')->update();
    }

    protected function tableAddModifiedColumnAfter(string $table, string $column): void
    {
        $this->table($table)
            ->addColumnDateTime('modified', ['after' => $column])
            ->update();
    }

    protected function tableAddModifierColumnAfter(string $table, string $column = 'modified'): void
    {
        $this->table($table)
            ->addColumnInteger('modifier_id', ['after' => $column, 'null' => true])
            ->update();
    }
}
