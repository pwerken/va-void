<?php

use Migrations\AbstractSeed;

/**
 * Factions seed.
 */
class FactionsSeed extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeds is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     *
     * @return void
     */
    public function run(): void
    {
        $data = [
            [
                'id' => '1',
                'name' => '-',
                'modified' => null,
            ],
            [
                'id' => '2',
                'name' => 'Gargoyle',
                'modified' => null,
            ],
            [
                'id' => '3',
                'name' => 'Wyvern',
                'modified' => null,
            ],
            [
                'id' => '4',
                'name' => 'Phoenix',
                'modified' => null,
            ],
            [
                'id' => '5',
                'name' => 'Leviathan',
                'modified' => null,
            ],
            [
                'id' => '6',
                'name' => 'Ent',
                'modified' => null,
            ],
            [
                'id' => '7',
                'name' => 'Unicorn',
                'modified' => null,
            ],
            [
                'id' => '8',
                'name' => 'Naga',
                'modified' => null,
            ],
            [
                'id' => '9',
                'name' => 'Metal',
                'modified' => null,
            ],
        ];

        $table = $this->table('factions');
        $table->insert($data)->save();
    }
}
