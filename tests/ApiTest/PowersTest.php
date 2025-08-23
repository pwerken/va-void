<?php
declare(strict_types=1);

namespace App\Test\ApiTest;

use App\Test\Fixture\TestAccount;
use App\Test\TestSuite\AuthIntegrationTestCase;

class PowersTest extends AuthIntegrationTestCase
{
    public function testAuthorizationGet(): void
    {
        $this->withoutAuth();
        $this->assertGet('/powers', 401);
        $this->assertGet('/powers/1', 401);
        $this->assertGet('/powers/1/characters', 401);
        $this->assertGet('/powers/2', 401);
        $this->assertGet('/powers/2/characters', 401);
        $this->assertGet('/powers/99', 401);
        $this->assertGet('/powers/99/characters', 401);

        $this->withAuthPlayer();
        $this->assertGet('/powers');
        $this->assertGet('/powers/1');
        $this->assertGet('/powers/1/characters', 403);
        $this->assertGet('/powers/2', 403);
        $this->assertGet('/powers/2/characters', 403);
        $this->assertGet('/powers/99', 404);
        $this->assertGet('/powers/99/characters', 403);

        $this->withAuthReadOnly();
        $this->assertGet('/powers');
        $this->assertGet('/powers/1');
        $this->assertGet('/powers/1/characters');
        $this->assertGet('/powers/2');
        $this->assertGet('/powers/2/characters');
        $this->assertGet('/powers/99', 404);
        $this->assertGet('/powers/99/characters', 404);

        $this->withAuthReferee();
        $this->assertGet('/powers');
        $this->assertGet('/powers/1');
        $this->assertGet('/powers/1/characters');
        $this->assertGet('/powers/2');
        $this->assertGet('/powers/2/characters');
        $this->assertGet('/powers/99', 404);
        $this->assertGet('/powers/99/characters', 404);

        $this->withAuthInfobalie();
        $this->assertGet('/powers');
        $this->assertGet('/powers/1');
        $this->assertGet('/powers/1/characters');
        $this->assertGet('/powers/2');
        $this->assertGet('/powers/2/characters');
        $this->assertGet('/powers/99', 404);
        $this->assertGet('/powers/99/characters', 404);
    }

    public function testAuthorizationPut(): void
    {
        $this->withoutAuth();
        $this->assertPut('/powers', [], 401);
        $this->assertPut('/powers/1', [], 401);
        $this->assertPut('/powers/2', [], 401);
        $this->assertPut('/powers/99', [], 401);

        $this->withAuthPlayer();
        $this->assertPut('/powers', [], 403);
        $this->assertPut('/powers/1', [], 403);
        $this->assertPut('/powers/2', [], 403);
        $this->assertPut('/powers/99', [], 403);

        $this->withAuthReadOnly();
        $this->assertPut('/powers', [], 403);
        $this->assertPut('/powers/1', [], 403);
        $this->assertPut('/powers/2', [], 403);
        $this->assertPut('/powers/99', [], 403);

        $this->withAuthReferee();
        $this->assertPut('/powers', [], 422);
        $this->assertPut('/powers/1', []);
        $this->assertPut('/powers/2', []);
        $this->assertPut('/powers/99', [], 404);

        $this->withAuthInfobalie();
        $this->assertPut('/powers', [], 422);
        $this->assertPut('/powers/1', []);
        $this->assertPut('/powers/2', []);
        $this->assertPut('/powers/99', [], 404);
    }

    public function testAuthorizationDelete(): void
    {
        $this->withoutAuth();
        $this->assertDelete('/powers/1', 401);
        $this->assertDelete('/powers/2', 401);
        $this->assertDelete('/powers/99', 401);

        $this->withAuthPlayer();
        $this->assertDelete('/powers/1', 403);
        $this->assertDelete('/powers/2', 403);
        $this->assertDelete('/powers/99', 403);

        $this->withAuthReadOnly();
        $this->assertDelete('/powers/1', 403);
        $this->assertDelete('/powers/2', 403);
        $this->assertDelete('/powers/99', 403);

        $this->withAuthReferee();
        $this->assertDelete('/powers/1', 403);
        $this->assertDelete('/powers/2', 403);
        $this->assertDelete('/powers/99', 403);

        $this->withAuthInfobalie();
        $this->assertDelete('/powers/1', 403);
        $this->assertDelete('/powers/2', 403);
        $this->assertDelete('/powers/99', 403);
    }

    public function testAddMinimal(): void
    {
        $input = [
# required fields:
            'name' => 'power name',
            'player_text' => 'player explenation',
        ];

        $expected = [
            'class' => 'Power',
            'url' => '/powers/3',
            'poin' => 3,
            'name' => $input['name'],
            'player_text' => $input['player_text'],
            'referee_notes' => null,
            'notes' => null,
            'deprecated' => false,
            'modifier_id' => TestAccount::Referee->value,
            'creator_id' => TestAccount::Referee->value,
        ];

        $this->withAuthReferee();
        $actual = $this->assertPut('/powers', $input, 201);

        foreach ($expected as $key => $value) {
            $this->assertArrayKeyValue($key, $value, $actual);
        }
        $this->assertDateTimeNow($actual['modified']);
        $this->assertDateTimeNow($actual['created']);
    }

    public function testAddComplete(): void
    {
        $input = [
# required fields:
            'name' => 'power name',
            'player_text' => 'player explenation',
# optional fields:
            'referee_notes' => 'hidden referee details',
            'notes' => 'infobalie notes',
            'deprecated' => true,
# ignored fields:
            'id' => 66,
            'modifier_id' => 9,
            'creator_id' => 9,
            'ignored' => 'ignored',
        ];

        $expected = [
            'class' => 'Power',
            'url' => '/powers/3',
            'poin' => 3,
            'name' => $input['name'],
            'player_text' => $input['player_text'],
            'referee_notes' => $input['referee_notes'],
            'notes' => $input['notes'],
            'deprecated' => $input['deprecated'],
            'modifier_id' => TestAccount::Referee->value,
            'creator_id' => TestAccount::Referee->value,
        ];

        $this->withAuthReferee();
        $actual = $this->assertPut('/powers', $input, 201);

        foreach ($expected as $key => $value) {
            $this->assertArrayKeyValue($key, $value, $actual);
        }
        $this->assertDateTimeNow($actual['modified']);
        $this->assertDateTimeNow($actual['created']);

        $this->assertArrayNotHasKey('ignored', $actual);
    }

    public function testAddValidation(): void
    {
        $input = [
# disallowed fields:
            'poin' => 55,
# required fields, not allowed empty
            'name' => '',
            'player_text' => '',
        ];

        $this->withAuthReferee();
        $response = $this->assertPut('/powers', $input, 422);

        $errors = $this->assertErrorsResponse('/powers', $response);

        # expected fields with validation errors:
        $this->assertCount(3, $errors);
        $this->assertArrayHasKey('poin', $errors);
        $this->assertArrayHasKey('name', $errors);
        $this->assertArrayHasKey('player_text', $errors);
    }

    public function testEditValidation(): void
    {
        $input = [
# disallowed fields:
            'poin' => 55,
# required fields, not allowed empty
            'name' => '',
            'player_text' => '',
        ];

        $this->withAuthReferee();
        $response = $this->assertPut('/powers/1', $input, 422);

        $errors = $this->assertErrorsResponse('/powers/1', $response);

        # expected fields with validation errors:
        $this->assertCount(3, $errors);
        $this->assertArrayHasKey('poin', $errors);
        $this->assertArrayHasKey('name', $errors);
        $this->assertArrayHasKey('player_text', $errors);
    }
}
