<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class CharactersImbuesFixture extends TestFixture
{
    public function init(): void
    {
        $this->records = [
            [
                'imbue_id' => 1,
                'character_id' => 1,
                'type' => 'rune',
                'times' => 1,
                'modified' => null,
                'modifier_id' => null,
            ],
            [
                'imbue_id' => 1,
                'character_id' => 1,
                'type' => 'glyph',
                'times' => 1,
                'modified' => null,
                'modifier_id' => null,
            ],
            [
                'imbue_id' => 2,
                'character_id' => 2,
                'type' => 'glyph',
                'times' => 2,
                'modified' => null,
                'modifier_id' => null,
            ],
        ];
        parent::init();
    }
}
