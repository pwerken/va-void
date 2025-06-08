<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class PowersFixture extends TestFixture
{
    public function init(): void
    {
        $this->records = [
            [
                'id' => 1,
                'name' => 'Magic',
                'player_text' => 'You can do magics',
                'referee_note' => '',
                'notes' => '',
                'deprecated' => false,
                'created' => null,
                'creator_id' => null,
                'modified' => null,
                'modifier_id' => null,
            ],
            [
                'id' => 2,
                'name' => 'Yes',
                'player_text' => 'You you can.',
                'referee_notes' => '',
                'notes' => '',
                'deprecated' => true,
                'created' => null,
                'creator_id' => null,
                'modified' => null,
                'modifier_id' => null,
            ],
        ];
        parent::init();
    }
}
