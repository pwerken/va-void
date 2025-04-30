<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class LammiesFixture extends TestFixture
{
    public function init(): void
    {
        $this->records = [
            [
                'id' => 1,
                'status' => 'Queued',
                'entity' => 'Character',
                'key1' => 1,
                'key2' => 1,
                'created' => '2018-10-28 12:17:05',
                'creator_id' => 1,
                'modified' => '2018-10-28 12:17:05',
            ],
            [
                'id' => 2,
                'status' => 'Queued',
                'entity' => 'CharactersCondition',
                'key1' => 1,
                'key2' => 1,
                'created' => '2018-10-28 12:17:05',
                'creator_id' => 1,
                'modified' => '2018-10-28 12:17:05',
            ],
            [
                'id' => 3,
                'status' => 'Queued',
                'entity' => 'CharactersPower',
                'key1' => 1,
                'key2' => 1,
                'created' => '2018-10-28 12:17:05',
                'creator_id' => 1,
                'modified' => '2018-10-28 12:17:05',
            ],
            [
                'id' => 4,
                'status' => 'Queued',
                'entity' => 'Condition',
                'key1' => 1,
                'key2' => null,
                'created' => '2018-10-28 12:17:05',
                'creator_id' => 1,
                'modified' => '2018-10-28 12:17:05',
            ],
            [
                'id' => 5,
                'status' => 'Queued',
                'entity' => 'Power',
                'key1' => 1,
                'key2' => null,
                'created' => '2018-10-28 12:17:05',
                'creator_id' => 1,
                'modified' => '2018-10-28 12:17:05',
            ],
            [
                'id' => 6,
                'status' => 'Queued',
                'entity' => 'Item',
                'key1' => 1,
                'key2' => null,
                'created' => '2018-10-28 12:17:05',
                'creator_id' => 1,
                'modified' => '2018-10-28 12:17:05',
            ],
            [
                'id' => 7,
                'status' => 'Queued',
                'entity' => 'Teaching',
                'key1' => 1,
                'key2' => 1,
                'created' => '2018-10-28 12:17:05',
                'creator_id' => 1,
                'modified' => '2018-10-28 12:17:05',
            ],
        ];
        parent::init();
    }
}
