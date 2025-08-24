<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class SocialProfilesFixture extends TestFixture
{
    public function init(): void
    {
        $this->records = [
            [
                'id' => 1,
                'user_id' => 1,
                'hidden' => 0,
                'provider' => 'google',
                'identifier' => 6,
                'username' => null,
                'full_name' => 'test',
                'email' => 'fake@example.com',
                'modified' => '2025-08-24 01:00:00',
                'created' => '2025-08-24 01:00:00',
            ],
        ];
        parent::init();
    }
}
