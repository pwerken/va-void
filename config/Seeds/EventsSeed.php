<?php

use Migrations\AbstractSeed;

/**
 * Events seed.
 */
class EventsSeed extends AbstractSeed
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
                'name' => 'Moots 1 2012',
                'modified' => null,
            ],
            [
                'id' => '2',
                'name' => 'Summoning 2012',
                'modified' => null,
            ],
            [
                'id' => '3',
                'name' => 'Moots 2 2012',
                'modified' => null,
            ],
            [
                'id' => '4',
                'name' => 'Moots 1 2013',
                'modified' => null,
            ],
            [
                'id' => '5',
                'name' => 'Summoning 2013',
                'modified' => null,
            ],
            [
                'id' => '6',
                'name' => 'Moots 2 2013',
                'modified' => null,
            ],
            [
                'id' => '7',
                'name' => 'Moots 1 2014',
                'modified' => null,
            ],
            [
                'id' => '8',
                'name' => 'Summoning 2014',
                'modified' => null,
            ],
            [
                'id' => '9',
                'name' => 'Moots 2 2014',
                'modified' => null,
            ],
            [
                'id' => '10',
                'name' => 'Moots 1 2015',
                'modified' => null,
            ],
            [
                'id' => '11',
                'name' => 'Summoning 2015',
                'modified' => null,
            ],
            [
                'id' => '12',
                'name' => 'Moots 2 2015',
                'modified' => null,
            ],
            [
                'id' => '13',
                'name' => 'Moots 1 2016',
                'modified' => null,
            ],
            [
                'id' => '14',
                'name' => 'Summoning 2016',
                'modified' => null,
            ],
            [
                'id' => '15',
                'name' => 'Moots 2 2016',
                'modified' => null,
            ],
            [
                'id' => '16',
                'name' => 'Moots 1 2017',
                'modified' => null,
            ],
        ];

        $table = $this->table('events');
        $table->insert($data)->save();
    }
}
