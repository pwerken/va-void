<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use App\Model\Enum\LammyStatus;
use Cake\TestSuite\Fixture\TestFixture;

class LammiesFixture extends TestFixture
{
    public function init(): void
    {
        $this->records = [
            [
                'id' => 1,
                'status' => LammyStatus::Queued,
                'entity' => 'Character',
                'key1' => 1,
                'key2' => 1,
                'created' => '2018-10-28 12:17:05',
                'creator_id' => 1,
                'modified' => '2018-10-28 12:17:05',
            ],
            [
                'id' => 2,
                'status' => LammyStatus::Queued,
                'entity' => 'CharactersCondition',
                'key1' => 1,
                'key2' => 1,
                'created' => '2018-10-28 12:17:05',
                'creator_id' => 1,
                'modified' => '2018-10-28 12:17:05',
            ],
            [
                'id' => 3,
                'status' => LammyStatus::Queued,
                'entity' => 'CharactersPower',
                'key1' => 1,
                'key2' => 1,
                'created' => '2018-10-28 12:17:05',
                'creator_id' => 1,
                'modified' => '2018-10-28 12:17:05',
            ],
            [
                'id' => 4,
                'status' => LammyStatus::Queued,
                'entity' => 'Condition',
                'key1' => 1,
                'key2' => null,
                'created' => '2018-10-28 12:17:05',
                'creator_id' => 1,
                'modified' => '2018-10-28 12:17:05',
            ],
            [
                'id' => 5,
                'status' => LammyStatus::Queued,
                'entity' => 'Power',
                'key1' => 1,
                'key2' => null,
                'created' => '2018-10-28 12:17:05',
                'creator_id' => 1,
                'modified' => '2018-10-28 12:17:05',
            ],
            [
                'id' => 6,
                'status' => LammyStatus::Queued,
                'entity' => 'Item',
                'key1' => 1,
                'key2' => null,
                'created' => '2018-10-28 12:17:05',
                'creator_id' => 1,
                'modified' => '2018-10-28 12:17:05',
            ],
            [
                'id' => 7,
                'status' => LammyStatus::Queued,
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
