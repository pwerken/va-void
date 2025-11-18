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
                'base_max' => 1,
                'times_max' => 3,
                'manatype_id' => 1,
                'mana_amount' => 10,
                'loresheet' => false,
                'blanks' => false,
                'sort_order' => 1,
                'deprecated' => false,
            ],
            [
                'id' => 2,
                'name' => 'Dreaming',
                'cost' => 2,
                'base_max' => 10,
                'times_max' => 10,
                'manatype_id' => null,
                'mana_amount' => 0,
                'loresheet' => false,
                'blanks' => false,
                'sort_order' => 1,
                'deprecated' => false,
            ],
            [
                'id' => 3,
                'name' => 'Walking',
                'cost' => 0,
                'base_max' => 1,
                'times_max' => 1,
                'manatype_id' => null,
                'mana_amount' => 0,
                'loresheet' => false,
                'blanks' => false,
                'sort_order' => 10,
                'deprecated' => true,
            ],
        ];
        parent::init();
    }
}
