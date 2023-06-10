<?php
use Migrations\AbstractSeed;

/**
 * Spells seed.
 */
class SpellsSeed extends AbstractSeed
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
                'name' => 'Air',
                'short' => 'Air',
                'spiritual' => '0',
            ],
            [
                'id' => '2',
                'name' => 'Earth',
                'short' => 'Earth',
                'spiritual' => '0',
            ],
            [
                'id' => '3',
                'name' => 'Fire',
                'short' => 'Fire',
                'spiritual' => '0',
            ],
            [
                'id' => '4',
                'name' => 'Metal',
                'short' => 'Metal',
                'spiritual' => '0',
            ],
            [
                'id' => '5',
                'name' => 'Water',
                'short' => 'Water',
                'spiritual' => '0',
            ],
            [
                'id' => '6',
                'name' => 'Wood',
                'short' => 'Wood',
                'spiritual' => '0',
            ],
            [
                'id' => '7',
                'name' => 'Body/Life',
                'short' => 'B/L',
                'spiritual' => '1',
            ],
            [
                'id' => '8',
                'name' => 'Chaos/Destruction',
                'short' => 'C/D',
                'spiritual' => '1',
            ],
            [
                'id' => '9',
                'name' => 'Civilization/Order',
                'short' => 'C/O',
                'spiritual' => '1',
            ],
            [
                'id' => '10',
                'name' => 'Death/Soul',
                'short' => 'D/S',
                'spiritual' => '1',
            ],
            [
                'id' => '11',
                'name' => 'Evil/Darkness',
                'short' => 'E/D',
                'spiritual' => '1',
            ],
            [
                'id' => '12',
                'name' => 'Good/Light',
                'short' => 'G/L',
                'spiritual' => '1',
            ],
            [
                'id' => '13',
                'name' => 'Mind/Knowledge',
                'short' => 'M/K',
                'spiritual' => '1',
            ],
            [
                'id' => '14',
                'name' => 'Nature/Creation',
                'short' => 'N/C',
                'spiritual' => '1',
            ],
        ];

        $table = $this->table('spells');
        $table->insert($data)->save();
    }
}
