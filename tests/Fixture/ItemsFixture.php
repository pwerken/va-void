<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class ItemsFixture extends TestFixture
{
    public function init(): void
    {
        $this->records = [
            [
                'id' => 1,
                'name' => 'Sword',
                'description' => '1-h sword',
                'player_text' => 'Magic sword',
                'referee_notes' => '',
                'notes' => '',
                'character_id' => 1,
                'expiry' => '2018-06-23',
                'deprecated' => true,
                'created' => null,
                'creator_id' => null,
                'modified' => '2018-06-23 14:30:19',
                'modifier_id' => 1,
            ],
            [
                'id' => 2,
                'name' => 'Shield',
                'description' => 'Shield',
                'player_text' => 'Pretty Shield',
                'referee_notes' => '',
                'notes' => '',
                'character_id' => 2,
                'expiry' => '2018-06-23',
                'deprecated' => false,
                'created' => null,
                'creator_id' => null,
                'modified' => '2018-06-23 14:30:19',
                'modifier_id' => 1,
            ],
        ];
        parent::init();
    }
}
