<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class SkillsFixture extends TestFixture
{
    public function init(): void
    {
        $this->records = [
            [
                'id' => 1,
                'name' => 'Casting',
                'cost' => 5,
                'times_max' => 3,
                'manatype_id' => 1,
                'mana_amount' => 10,
                'loresheet' => 0,
                'blanks' => 0,
                'sort_order' => 1,
                'deprecated' => false,
            ],
            [
                'id' => 2,
                'name' => 'Dreaming',
                'cost' => 2,
                'times_max' => 10,
                'manatype_id' => null,
                'mana_amount' => 0,
                'loresheet' => 0,
                'blanks' => 0,
                'sort_order' => 1,
                'deprecated' => false,
            ],
        ];
        parent::init();
    }
}
