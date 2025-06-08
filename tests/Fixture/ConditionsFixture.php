<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class ConditionsFixture extends TestFixture
{
    public function init(): void
    {
        $this->records = [
            [
                'id' => 1,
                'name' => 'Glasses',
                'player_text' => 'You require glasses.',
                'referee_notes' => '',
                'notes' => 'Not just the drinking kind.',
                'deprecated' => false,
                'created' => null,
                'creator_id' => null,
                'modified' => null,
                'modifier_id' => null,
            ],
            [
                'id' => 2,
                'name' => 'No',
                'player_text' => 'No you can\'t.',
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
