<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Authentication\PasswordHasher\DefaultPasswordHasher;
use Cake\TestSuite\Fixture\TestFixture;

class PlayersFixture extends TestFixture
{
    public function init(): void
    {
        $hasher = new DefaultPasswordHasher();
        $password = $hasher->hash('password');

        $this->records = [
            [
                'id' => TestAccount::Player->value,
                'role' => 'Player',
                'password' => $password,
                'first_name' => 'Player',
                'insertion' => null,
                'last_name' => 'One',
                'gender' => null,
                'date_of_birth' => null,
                'modified' => null,
                'modifier_id' => null,
            ],
            [
                'id' => TestAccount::ReadOnly->value,
                'role' => 'Read-only',
                'password' => $password,
                'first_name' => 'Read',
                'insertion' => null,
                'last_name' => 'Only',
                'gender' => null,
                'date_of_birth' => null,
                'modified' => '2018-01-01 01:00:00',
                'modifier_id' => 5,
            ],
            [
                'id' => TestAccount::Referee->value,
                'role' => 'Referee',
                'password' => $password,
                'first_name' => 'Centrale',
                'insertion' => '',
                'last_name' => 'Spelleiding',
                'gender' => 'F',
                'date_of_birth' => '1987-06-05',
                'modified' => '2018-01-01 01:00:00',
                'modifier_id' => 5,
            ],
            [
                'id' => TestAccount::Infobalie->value,
                'role' => 'Infobalie',
                'password' => $password,
                'first_name' => 'In',
                'insertion' => 'fo',
                'last_name' => 'Balie',
                'gender' => 'M',
                'date_of_birth' => '1987-06-05',
                'modified' => null,
                'modifier_id' => 1,
            ],
            [
                'id' => TestAccount::Super->value,
                'role' => 'Super',
                'password' => $password,
                'first_name' => 'Super',
                'insertion' => null,
                'last_name' => 'User',
                'gender' => 'F',
                'date_of_birth' => '1987-06-05',
                'modified' => null,
                'modifier_id' => null,
            ],
            [
                'id' => 6,
                'role' => 'Player',
                'password' => null,
                'first_name' => 'no',
                'insertion' => null,
                'last_name' => 'login',
                'gender' => null,
                'date_of_birth' => null,
                'modified' => null,
                'modifier_id' => null,
            ],
        ];
        parent::init();
    }
}
