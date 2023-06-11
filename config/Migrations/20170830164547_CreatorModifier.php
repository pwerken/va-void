<?php
use App\Migrations\AppMigration;

class CreatorModifier extends AppMigration
{
    public function up()
    {

        $this->table('attributes_items')
            ->addColumn('modified', 'datetime', [
                'after' => 'item_id',
                'default' => null,
                'length' => null,
                'null' => true,
            ])
            ->addColumn('modifier_id', 'integer', [
                'after' => 'modified',
                'default' => null,
                'length' => 11,
                'null' => true,
            ])
            ->update();

        $this->table('believes')
            ->addColumn('modifier_id', 'integer', [
                'after' => 'modified',
                'default' => null,
                'length' => 11,
                'null' => true,
            ])
            ->update();

        $this->table('characters')
            ->addColumn('modifier_id', 'integer', [
                'after' => 'modified',
                'default' => null,
                'length' => 11,
                'null' => true,
            ])
            ->update();

        $this->table('characters_conditions')
            ->addColumn('modified', 'datetime', [
                'after' => 'expiry',
                'default' => null,
                'length' => null,
                'null' => true,
            ])
            ->addColumn('modifier_id', 'integer', [
                'after' => 'modified',
                'default' => null,
                'length' => 11,
                'null' => true,
            ])
            ->update();

        $this->table('characters_powers')
            ->addColumn('modified', 'datetime', [
                'after' => 'expiry',
                'default' => null,
                'length' => null,
                'null' => true,
            ])
            ->addColumn('modifier_id', 'integer', [
                'after' => 'modified',
                'default' => null,
                'length' => 11,
                'null' => true,
            ])
            ->update();

        $this->table('characters_skills')
            ->addColumn('modified', 'datetime', [
                'after' => 'skill_id',
                'default' => null,
                'length' => null,
                'null' => true,
            ])
            ->addColumn('modifier_id', 'integer', [
                'after' => 'modified',
                'default' => null,
                'length' => 11,
                'null' => true,
            ])
            ->update();

        $this->table('characters_spells')
            ->addColumn('modified', 'datetime', [
                'after' => 'level',
                'default' => null,
                'length' => null,
                'null' => true,
            ])
            ->addColumn('modifier_id', 'integer', [
                'after' => 'modified',
                'default' => null,
                'length' => 11,
                'null' => true,
            ])
            ->update();

        $this->table('conditions')
            ->addColumn('modifier_id', 'integer', [
                'after' => 'modified',
                'default' => null,
                'length' => 11,
                'null' => true,
            ])
            ->update();

        $this->table('events')
            ->addColumn('modifier_id', 'integer', [
                'after' => 'modified',
                'default' => null,
                'length' => 11,
                'null' => true,
            ])
            ->update();

        $this->table('factions')
            ->addColumn('modifier_id', 'integer', [
                'after' => 'modified',
                'default' => null,
                'length' => 11,
                'null' => true,
            ])
            ->update();

        $this->table('groups')
            ->addColumn('modifier_id', 'integer', [
                'after' => 'modified',
                'default' => null,
                'length' => 11,
                'null' => true,
            ])
            ->update();

        $this->table('items')
            ->addColumn('modifier_id', 'integer', [
                'after' => 'modified',
                'default' => null,
                'length' => 11,
                'null' => true,
            ])
            ->update();

        $this->table('lammies')
            ->addColumn('creator_id', 'integer', [
                'after' => 'created',
                'default' => null,
                'length' => 11,
                'null' => true,
            ])
            ->update();

        $this->table('players')
            ->addColumn('modifier_id', 'integer', [
                'after' => 'modified',
                'default' => null,
                'length' => 11,
                'null' => true,
            ])
            ->update();

        $this->table('powers')
            ->addColumn('modifier_id', 'integer', [
                'after' => 'modified',
                'default' => null,
                'length' => 11,
                'null' => true,
            ])
            ->update();

        $this->table('teachings')
            ->addColumn('modifier_id', 'integer', [
                'after' => 'modified',
                'default' => null,
                'length' => 11,
                'null' => true,
            ])
            ->update();

        $this->table('worlds')
            ->addColumn('modifier_id', 'integer', [
                'after' => 'modified',
                'default' => null,
                'length' => 11,
                'null' => true,
            ])
            ->update();
    }

    public function down()
    {

        $this->table('attributes_items')
            ->removeColumn('modified')
            ->removeColumn('modifier_id')
            ->update();

        $this->table('believes')
            ->removeColumn('modifier_id')
            ->update();

        $this->table('characters')
            ->removeColumn('modifier_id')
            ->update();

        $this->table('characters_conditions')
            ->removeColumn('modified')
            ->removeColumn('modifier_id')
            ->update();

        $this->table('characters_powers')
            ->removeColumn('modified')
            ->removeColumn('modifier_id')
            ->update();

        $this->table('characters_skills')
            ->removeColumn('modified')
            ->removeColumn('modifier_id')
            ->update();

        $this->table('characters_spells')
            ->removeColumn('modified')
            ->removeColumn('modifier_id')
            ->update();

        $this->table('conditions')
            ->removeColumn('modifier_id')
            ->update();

        $this->table('events')
            ->removeColumn('modifier_id')
            ->update();

        $this->table('factions')
            ->removeColumn('modifier_id')
            ->update();

        $this->table('groups')
            ->removeColumn('modifier_id')
            ->update();

        $this->table('items')
            ->removeColumn('modifier_id')
            ->update();

        $this->table('lammies')
            ->removeColumn('creator_id')
            ->update();

        $this->table('players')
            ->removeColumn('modifier_id')
            ->update();

        $this->table('powers')
            ->removeColumn('modifier_id')
            ->update();

        $this->table('teachings')
            ->removeColumn('modifier_id')
            ->update();

        $this->table('worlds')
            ->removeColumn('modifier_id')
            ->update();
    }
}

