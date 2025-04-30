<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class FactionsFixture extends TestFixture
{
    public function init(): void
    {
        $this->records = [
            [
                'id' => 1,
                'name' => '-',
                'modified' => null,
                'modifier_id' => null,
            ],
            [
                'id' => 2,
                'name' => 'Void',
                'modified' => null,
                'modifier_id' => null,
            ],
        ];
        parent::init();
    }
}
