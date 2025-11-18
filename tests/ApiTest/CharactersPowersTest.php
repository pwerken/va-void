<?php
declare(strict_types=1);

namespace App\Test\ApiTest;

use App\Test\Fixture\TestAccount;
use App\Test\TestSuite\AuthIntegrationTestCase;

class CharactersPowersTest extends AuthIntegrationTestCase
{
    public function testAuthorizationGet(): void
    {
        $this->withoutAuth();
        $this->assertGet('/characters/1/1/powers', 401);
        $this->assertGet('/characters/1/1/powers/1', 401);
        $this->assertGet('/characters/1/1/powers/2', 401);
        $this->assertGet('/characters/1/2/powers', 401);
        $this->assertGet('/characters/1/2/powers/1', 401);
        $this->assertGet('/characters/1/2/powers/2', 401);
        $this->assertGet('/characters/1/99/powers', 401);
        $this->assertGet('/characters/1/99/powers/1', 401);
        $this->assertGet('/characters/1/99/powers/2', 401);
        $this->assertGet('/characters/2/1/powers', 401);
        $this->assertGet('/characters/2/1/powers/1', 401);
        $this->assertGet('/characters/2/1/powers/2', 401);
        $this->assertGet('/characters/2/2/powers', 401);
        $this->assertGet('/characters/2/2/powers/1', 401);
        $this->assertGet('/characters/2/2/powers/2', 401);
        $this->assertGet('/characters/99/1/powers', 401);
        $this->assertGet('/characters/99/1/powers/1', 401);
        $this->assertGet('/characters/99/1/powers/2', 401);

        $this->withAuthPlayer();
        $this->assertGet('/characters/1/1/powers');
        $this->assertGet('/characters/1/1/powers/1');
        $this->assertGet('/characters/1/1/powers/2', 404);
        $this->assertGet('/characters/1/2/powers');
        $this->assertGet('/characters/1/2/powers/1', 404);
        $this->assertGet('/characters/1/2/powers/2', 404);
        $this->assertGet('/characters/1/99/powers', 404);
        $this->assertGet('/characters/1/99/powers/1', 404);
        $this->assertGet('/characters/1/99/powers/2', 404);
        $this->assertGet('/characters/2/1/powers', 403);
        $this->assertGet('/characters/2/1/powers/1', 403);
        $this->assertGet('/characters/2/1/powers/2', 403);
        $this->assertGet('/characters/99/1/powers', 403);
        $this->assertGet('/characters/99/1/powers/1', 403);
        $this->assertGet('/characters/99/1/powers/2', 403);

        $this->withAuthReadOnly();
        $this->assertGet('/characters/1/1/powers');
        $this->assertGet('/characters/1/1/powers/1');
        $this->assertGet('/characters/1/1/powers/2', 404);
        $this->assertGet('/characters/1/2/powers');
        $this->assertGet('/characters/1/2/powers/1', 404);
        $this->assertGet('/characters/1/2/powers/2', 404);
        $this->assertGet('/characters/1/99/powers', 404);
        $this->assertGet('/characters/1/99/powers/1', 404);
        $this->assertGet('/characters/1/99/powers/2', 404);
        $this->assertGet('/characters/2/1/powers');
        $this->assertGet('/characters/2/1/powers/1');
        $this->assertGet('/characters/2/1/powers/2');
        $this->assertGet('/characters/99/1/powers', 404);
        $this->assertGet('/characters/99/1/powers/1', 404);
        $this->assertGet('/characters/99/1/powers/2', 404);
    }

    public function testAuthorizationPut(): void
    {
        $this->withoutAuth();
        $this->assertPut('/characters/1/1/powers', [], 401);
        $this->assertPut('/characters/1/1/powers/1', [], 401);
        $this->assertPut('/characters/1/1/powers/2', [], 401);
        $this->assertPut('/characters/1/2/powers', [], 401);
        $this->assertPut('/characters/1/2/powers/1', [], 401);
        $this->assertPut('/characters/1/2/powers/2', [], 401);
        $this->assertPut('/characters/1/99/powers', [], 401);
        $this->assertPut('/characters/1/99/powers/1', [], 401);
        $this->assertPut('/characters/1/99/powers/2', [], 401);
        $this->assertPut('/characters/2/1/powers', [], 401);
        $this->assertPut('/characters/2/1/powers/1', [], 401);
        $this->assertPut('/characters/2/1/powers/2', [], 401);
        $this->assertPut('/characters/99/1/powers', [], 401);
        $this->assertPut('/characters/99/1/powers/1', [], 401);
        $this->assertPut('/characters/99/1/powers/2', [], 401);

        $this->withAuthPlayer();
        $this->assertPut('/characters/1/1/powers', [], 403);
        $this->assertPut('/characters/1/1/powers/1', [], 403);
        $this->assertPut('/characters/1/1/powers/2', [], 403);
        $this->assertPut('/characters/1/2/powers', [], 403);
        $this->assertPut('/characters/1/2/powers/1', [], 403);
        $this->assertPut('/characters/1/2/powers/2', [], 403);
        $this->assertPut('/characters/1/99/powers', [], 403);
        $this->assertPut('/characters/1/99/powers/1', [], 403);
        $this->assertPut('/characters/1/99/powers/2', [], 403);
        $this->assertPut('/characters/2/1/powers', [], 403);
        $this->assertPut('/characters/2/1/powers/1', [], 403);
        $this->assertPut('/characters/2/1/powers/2', [], 403);
        $this->assertPut('/characters/99/1/powers', [], 403);
        $this->assertPut('/characters/99/1/powers/1', [], 403);
        $this->assertPut('/characters/99/1/powers/2', [], 403);

        $this->withAuthReadOnly();
        $this->assertPut('/characters/1/1/powers', [], 403);
        $this->assertPut('/characters/1/1/powers/1', [], 403);
        $this->assertPut('/characters/1/1/powers/2', [], 403);
        $this->assertPut('/characters/1/2/powers', [], 403);
        $this->assertPut('/characters/1/2/powers/1', [], 403);
        $this->assertPut('/characters/1/2/powers/2', [], 403);
        $this->assertPut('/characters/1/99/powers', [], 403);
        $this->assertPut('/characters/1/99/powers/1', [], 403);
        $this->assertPut('/characters/1/99/powers/2', [], 403);
        $this->assertPut('/characters/2/1/powers', [], 403);
        $this->assertPut('/characters/2/1/powers/1', [], 403);
        $this->assertPut('/characters/2/1/powers/2', [], 403);
        $this->assertPut('/characters/99/1/powers', [], 403);
        $this->assertPut('/characters/99/1/powers/1', [], 403);
        $this->assertPut('/characters/99/1/powers/2', [], 403);
    }

    public function testAuthorizationDelete(): void
    {
        $this->withoutAuth();
        $this->assertDelete('/characters/1/1/powers/1', 401);
        $this->assertDelete('/characters/1/1/powers/2', 401);
        $this->assertDelete('/characters/1/2/powers/1', 401);
        $this->assertDelete('/characters/1/2/powers/2', 401);
        $this->assertDelete('/characters/1/99/powers/1', 401);
        $this->assertDelete('/characters/1/99/powers/2', 401);
        $this->assertDelete('/characters/2/1/powers/1', 401);
        $this->assertDelete('/characters/2/1/powers/2', 401);
        $this->assertDelete('/characters/99/1/powers/1', 401);
        $this->assertDelete('/characters/99/1/powers/2', 401);

        $this->withAuthPlayer();
        $this->assertDelete('/characters/1/1/powers/1', 403);
        $this->assertDelete('/characters/1/1/powers/2', 403);
        $this->assertDelete('/characters/1/2/powers/1', 403);
        $this->assertDelete('/characters/1/2/powers/2', 403);
        $this->assertDelete('/characters/1/99/powers/1', 403);
        $this->assertDelete('/characters/1/99/powers/2', 403);
        $this->assertDelete('/characters/2/1/powers/1', 403);
        $this->assertDelete('/characters/2/1/powers/2', 403);
        $this->assertDelete('/characters/99/1/powers/1', 403);
        $this->assertDelete('/characters/99/1/powers/2', 403);

        $this->withAuthReadOnly();
        $this->assertDelete('/characters/1/1/powers/1', 403);
        $this->assertDelete('/characters/1/1/powers/2', 403);
        $this->assertDelete('/characters/1/2/powers/1', 403);
        $this->assertDelete('/characters/1/2/powers/2', 403);
        $this->assertDelete('/characters/1/99/powers/1', 403);
        $this->assertDelete('/characters/1/99/powers/2', 403);
        $this->assertDelete('/characters/2/1/powers/1', 403);
        $this->assertDelete('/characters/2/1/powers/2', 403);
        $this->assertDelete('/characters/99/1/powers/1', 403);
        $this->assertDelete('/characters/99/1/powers/2', 403);
    }

    public function testRequiredFieldsValidation(): void
    {
        $url = '/characters/1/1/powers';

        $this->withAuthReferee();
        $response = $this->assertPut($url, [], 422);

        $errors = $this->assertErrorsResponse($url, $response);

        # expected fields with validation errors:
        $this->assertCount(1, $errors);
        $this->assertArrayHasKey('power_id', $errors);
    }

    public function testAddMinimal(): void
    {
        $input = [
# required fields:
            'power_id' => 2,
        ];

        $expected = [
            'class' => 'CharactersPower',
            'url' => '/characters/1/1/powers/2',
            'expiry' => null,
            'modifier_id' => TestAccount::Referee->value,
        ];

        $this->withAuthReferee();
        $actual = $this->assertPut('/characters/1/1/powers', $input, 201);

        foreach ($expected as $key => $value) {
            $this->assertArrayKeyValue($key, $value, $actual);
        }
        $this->assertDateTimeNow($actual['modified']);
    }

    public function testAddComplete(): void
    {
        $input = [
# required fields:
            'power_id' => 2,
# optional fields:
            'expiry' => '2025-05-29',
        ];

        $expected = [
            'class' => 'CharactersPower',
            'url' => '/characters/1/1/powers/2',
            'expiry' => $input['expiry'],
            'modifier_id' => TestAccount::Referee->value,
        ];

        $this->withAuthReferee();
        $actual = $this->assertPut('/characters/1/1/powers', $input, 201);

        foreach ($expected as $key => $value) {
            $this->assertArrayKeyValue($key, $value, $actual);
        }
        $this->assertDateTimeNow($actual['modified']);
    }

    public function testAddToConceptCharacter(): void
    {
        $input = [
            'power_id' => 2,
        ];

        $this->withAuthReferee();
        $actual = $this->assertPut('/characters/1/2/powers', $input, 422);

        $errors = $this->assertErrorsResponse('/characters/1/2/powers', $actual);
        # expected fields with validation errors:
        $this->assertCount(1, $errors);
        $this->assertArrayHasKey('character_id', $errors);
    }

    public function testEdit(): void
    {
        $input = [
# optional fields:
            'expiry' => '2025-05-29',
        ];

        $expected = [
            'class' => 'CharactersPower',
            'url' => '/characters/1/1/powers/1',
            'expiry' => $input['expiry'],
            'modifier_id' => TestAccount::Referee->value,
        ];

        $this->withAuthReferee();
        $actual = $this->assertPut('/characters/1/1/powers/1', $input);

        foreach ($expected as $key => $value) {
            $this->assertArrayKeyValue($key, $value, $actual);
        }
        $this->assertDateTimeNow($actual['modified']);
    }

    public function testDelete(): void
    {
        $this->withAuthReferee();
        $this->assertGet('/characters/1/1/powers/1');
        $this->assertDelete('/characters/1/1/powers/1');
        $this->assertGet('/characters/1/1/powers/1', 404);
    }
}
