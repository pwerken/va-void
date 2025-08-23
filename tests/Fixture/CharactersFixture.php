<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use App\Model\Enum\CharacterStatus;
use Cake\TestSuite\Fixture\TestFixture;

class CharactersFixture extends TestFixture
{
    public function init(): void
    {
        $this->records = [
            [
                'id' => 1,
                'plin' => 1,
                'chin' => 1,
                'name' => 'Sir Killalot',
                'xp' => 15.0,
                'faction_id' => 1,
                'belief' => '-',
                'group' => '-',
                'world' => '-',
                'status' => CharacterStatus::Active,
                'referee_notes' => '',
                'notes' => '',
                'modified' => null,
                'modifier_id' => null,
            ],
            [
                'id' => 2,
                'plin' => 2,
                'chin' => 1,
                'name' => 'Mathilda',
                'xp' => 15.0,
                'faction_id' => 2,
                'belief' => 'Self',
                'group' => 'The Gang',
                'world' => 'Home',
                'status' => CharacterStatus::Active,
                'referee_notes' => '',
                'notes' => '',
                'modified' => null,
                'modifier_id' => null,
            ],
        ];
        parent::init();
    }
}
