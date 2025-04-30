<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class AttributesFixture extends TestFixture
{
    public function init(): void
    {
        $this->records = [
            [
                'id' => 1,
                'name' => 'Magic',
                'category' => 'property',
                'code' => '01',
            ],
            [
                'id' => 2,
                'name' => 'Shiny',
                'category' => 'property',
                'code' => 'SH',
            ],
        ];
        parent::init();
    }
}
