<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class ImbuesFixture extends TestFixture
{
    public function init(): void
    {
        $this->records = [
            [
                'id' => 1,
                'name' => 'Tuna',
                'cost' => 6,
                'description' => 'You can tune a piano, ...',
                'notes' => '',
                'times_max' => 3,
                'deprecated' => false,
                'modified' => null,
                'modifier_id' => null,
            ],
            [
                'id' => 2,
                'name' => 'Old Magicks',
                'cost' => 12,
                'description' => 'Sparkles!',
                'notes' => '',
                'times_max' => 5,
                'deprecated' => false,
                'modified' => null,
                'modifier_id' => null,
            ],
            [
                'id' => 3,
                'name' => 'Ancient Horrors',
                'cost' => 1,
                'description' => 'No longer...',
                'notes' => '',
                'times_max' => 1,
                'deprecated' => true,
                'modified' => null,
                'modifier_id' => null,
            ],
        ];
        parent::init();
    }
}
