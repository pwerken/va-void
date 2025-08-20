<?php
declare(strict_types=1);

namespace App\Test\ApiTest;

use App\Test\Fixture\TestAccount;
use App\Test\TestSuite\AuthIntegrationTestCase;

class CharactersTest extends AuthIntegrationTestCase
{
    public function testAuthorization(): void
    {
        $this->withoutAuth();
        $this->assertGet('/characters', 401);
        $this->assertGet('/characters/1', 401);
        $this->assertGet('/characters/1/1', 401);
        $this->assertGet('/characters/1/1/items', 401);
        $this->assertGet('/characters/1/2', 401);
        $this->assertGet('/characters/1/2/items', 401);
        $this->assertGet('/characters/2', 401);
        $this->assertGet('/characters/2/1', 401);
        $this->assertGet('/characters/2/1/items', 401);
        $this->assertGet('/characters/2/2', 401);
        $this->assertGet('/characters/2/2/items', 401);
        $this->assertGet('/characters/99', 401);
        $this->assertGet('/characters/99/1', 401);
        $this->assertGet('/characters/99/1/items', 401);
        $this->assertDelete('/characters/1/1', 401);
        $this->assertDelete('/characters/1/2', 401);
        $this->assertDelete('/characters/2/1', 401);
        $this->assertDelete('/characters/99/1', 401);

        $this->withAuthPlayer();
        $this->assertGet('/characters');
        $this->assertGet('/characters/1');
        $this->assertGet('/characters/1/1');
        $this->assertGet('/characters/1/1/items');
        $this->assertGet('/characters/1/2', 404);
        $this->assertGet('/characters/1/2/items', 404);
        $this->assertGet('/characters/2', 403);
        $this->assertGet('/characters/2/1', 403);
        $this->assertGet('/characters/2/1/items', 403);
        $this->assertGet('/characters/99', 403);
        $this->assertGet('/characters/99/1', 403);
        $this->assertGet('/characters/99/1/items', 403);
        $this->assertDelete('/characters/1/1', 403);
        $this->assertDelete('/characters/1/2', 403);
        $this->assertDelete('/characters/2/1', 403);
        $this->assertDelete('/characters/99/1', 403);

        $this->withAuthReadOnly();
        $this->assertGet('/characters');
        $this->assertGet('/characters/1');
        $this->assertGet('/characters/1/1');
        $this->assertGet('/characters/1/1/items');
        $this->assertGet('/characters/1/2', 404);
        $this->assertGet('/characters/1/2/items', 404);
        $this->assertGet('/characters/2');
        $this->assertGet('/characters/2/1');
        $this->assertGet('/characters/2/1/items');
        $this->assertGet('/characters/99', 404);
        $this->assertGet('/characters/99/1', 404);
        $this->assertGet('/characters/99/1/items', 404);
        $this->assertDelete('/characters/1/1', 403);
        $this->assertDelete('/characters/1/2', 403);
        $this->assertDelete('/characters/2/1', 403);
        $this->assertDelete('/characters/99/1', 403);

        $this->withAuthReferee();
        $this->assertDelete('/characters/1/1', 403);
        $this->assertDelete('/characters/1/2', 403);
        $this->assertDelete('/characters/2/1', 403);
        $this->assertDelete('/characters/99/1', 403);

        $this->withAuthInfobalie();
        $this->assertDelete('/characters/1/1', 403);
        $this->assertDelete('/characters/1/2', 403);
        $this->assertDelete('/characters/2/1', 403);
        $this->assertDelete('/characters/99/1', 403);
    }

    public function testBelieves(): void
    {
        $this->withoutAuth();
        $this->assertGet('/believes', 401);

        $this->withAuthPlayer();
        $this->assertGet('/believes');
    }

    public function testGroups(): void
    {
        $this->withoutAuth();
        $this->assertGet('/groups', 401);

        $this->withAuthPlayer();
        $this->assertGet('/groups');
    }

    public function testWorlds(): void
    {
        $this->withoutAuth();
        $this->assertGet('/worlds', 401);

        $this->withAuthPlayer();
        $this->assertGet('/worlds');
    }

    public function testRequiredFieldsValidation(): void
    {
        $url = '/players/1/characters';

        $this->withAuthReferee();
        $response = $this->assertPut($url, [], 422);

        $errors = $this->assertErrorsResponse($url, $response);
        # expected fields with validation errors:
        $this->assertCount(1, $errors);
        $this->assertArrayHasKey('name', $errors);
    }

    public function testAddMinimal(): void
    {
        $input = [
# required fields:
            'name' => 'new character',
        ];

        $expected = [
            'class' => 'Character',
            'url' => '/characters/2/2',
            'plin' => 2,
            'chin' => 2,
            'name' => $input['name'],
            'xp' => 15.0,
            'belief' => '-',
            'group' => '-',
            'world' => '-',
            'faction' => '-',
            'status' => 'inactive',
            'referee_notes' => null,
            'notes' => null,
            'modifier_id' => TestAccount::Referee->value,
        ];

        $this->withAuthReferee();
        $actual = $this->assertPut('/players/2/characters', $input, 201);

        foreach ($expected as $key => $value) {
            $this->assertArrayKeyValue($key, $value, $actual);
        }
        $this->assertDateTimeNow($actual['modified']);
    }

    public function testAddComplete(): void
    {
        $input = [
# required fields:
            'name' => 'new character',
# optional fields:
            'chin' => 5,
            'xp' => '20.25',
            'faction' => 'Void',
            'belief' => 'Solar',
            'group' => 'hullie',
            'world' => 'home',
            'status' => 'dead',
            'referee_notes' => 'hidden referee details',
            'notes' => 'infobalie notes',
# ignored fields:
            'plin' => 9,
            'modifier_id' => 9,
            'ignored' => 'ignored',
        ];

        $expected = [
            'class' => 'Character',
            'url' => '/characters/2/5',
            'plin' => 2,
            'chin' => $input['chin'],
            'name' => $input['name'],
            'xp' => $input['xp'],
            'faction' => $input['faction'],
            'belief' => $input['belief'],
            'group' => $input['group'],
            'world' => $input['world'],
            'status' => $input['status'],
            'referee_notes' => $input['referee_notes'],
            'notes' => $input['notes'],
            'modifier_id' => TestAccount::Referee->value,
        ];

        $this->withAuthReferee();
        $actual = $this->assertPut('/players/2/characters', $input, 201);

        foreach ($expected as $key => $value) {
            $this->assertArrayKeyValue($key, $value, $actual);
        }
        $this->assertDateTimeNow($actual['modified']);
    }

    public function testEdit(): void
    {
        $input = [
# disallowed fields:
            'plin' => 9,
            'chin' => 5,
# required fields:
            'name' => 'new character',
# optional fields:
            'xp' => '20.25',
            'faction' => 'Void',
            'belief' => 'Solar',
            'group' => 'hullie',
            'world' => 'home',
            'status' => 'dead',
            'referee_notes' => 'hidden referee details',
            'notes' => 'infobalie notes',
# ignored fields:
            'modifier_id' => 9,
            'ignored' => 'ignored',
        ];

        $this->withAuthReferee();
        $actual = $this->assertPut('/characters/1/1', $input, 422);

        $errors = $this->assertErrorsResponse('/characters/1/1', $actual);
        # expected fields with validation errors:
        $this->assertCount(2, $errors);
        $this->assertArrayHasKey('player_id', $errors); # FIXME? -> plin
        $this->assertArrayHasKey('chin', $errors);

        unset($input['plin']);
        unset($input['chin']);
        $expected = [
            'class' => 'Character',
            'url' => '/characters/1/1',
            'plin' => 1,
            'chin' => 1,
            'name' => $input['name'],
            'xp' => $input['xp'],
            'faction' => $input['faction'],
            'belief' => $input['belief'],
            'group' => $input['group'],
            'world' => $input['world'],
            'status' => $input['status'],
            'referee_notes' => $input['referee_notes'],
            'notes' => $input['notes'],
            'modifier_id' => TestAccount::Referee->value,
        ];

        $actual = $this->assertPut('/characters/1/1', $input);

        foreach ($expected as $key => $value) {
            $this->assertArrayKeyValue($key, $value, $actual);
        }
        $this->assertDateTimeNow($actual['modified']);
    }
}
