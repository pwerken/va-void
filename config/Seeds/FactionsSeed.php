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
                'modified' => NULL,
            ],
            [
                'id' => '2',
                'name' => 'Gargoyle',
                'modified' => NULL,
            ],
            [
                'id' => '3',
                'name' => 'Wyvern',
                'modified' => NULL,
            ],
            [
                'id' => '4',
                'name' => 'Phoenix',
                'modified' => NULL,
            ],
            [
                'id' => '5',
                'name' => 'Leviathan',
                'modified' => NULL,
            ],
            [
                'id' => '6',
                'name' => 'Ent',
                'modified' => NULL,
            ],
            [
                'id' => '7',
                'name' => 'Unicorn',
                'modified' => NULL,
            ],
            [
                'id' => '8',
                'name' => 'Naga',
                'modified' => NULL,
            ],
            [
                'id' => '9',
                'name' => 'Metal',
                'modified' => NULL,
            ],
        ];

        $table = $this->table('factions');
        $table->insert($data)->save();
    }
}
