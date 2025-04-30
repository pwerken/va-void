<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class EventsFixture extends TestFixture
{
    public function init(): void
    {
        $this->records = [
            [
                'id' => 1,
                'name' => 'Latest',
                'modified' => null,
                'modifier_id' => null,
            ],
        ];
        parent::init();
    }
}
