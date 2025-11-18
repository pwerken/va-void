<?php
declare(strict_types=1);

namespace App\Test\ApiTest;

use App\Test\Fixture\TestAccount;
use App\Test\TestSuite\AuthIntegrationTestCase;

class CharactersRuneImbuesTest extends AuthIntegrationTestCase
{
    public function testAuthorizationGet(): void
    {
        $this->withoutAuth();
        $this->assertGet('/characters/1/1/runeimbues', 401);
        $this->assertGet('/characters/1/1/runeimbues/1', 401);
        $this->assertGet('/characters/1/1/runeimbues/2', 401);
        $this->assertGet('/characters/1/2/runeimbues', 401);
        $this->assertGet('/characters/1/2/runeimbues/1', 401);
        $this->assertGet('/characters/1/2/runeimbues/2', 401);
        $this->assertGet('/characters/1/99/runeimbues', 401);
        $this->assertGet('/characters/1/99/runeimbues/1', 401);
        $this->assertGet('/characters/1/99/runeimbues/2', 401);
        $this->assertGet('/characters/2/1/runeimbues', 401);
        $this->assertGet('/characters/2/1/runeimbues/1', 401);
        $this->assertGet('/characters/2/1/runeimbues/2', 401);
        $this->assertGet('/characters/2/2/runeimbues', 401);
        $this->assertGet('/characters/2/2/runeimbues/1', 401);
        $this->assertGet('/characters/2/2/runeimbues/2', 401);
        $this->assertGet('/characters/99/1/runeimbues', 401);
        $this->assertGet('/characters/99/1/runeimbues/1', 401);
        $this->assertGet('/characters/99/1/runeimbues/2', 401);

        $this->withAuthPlayer();
        $this->assertGet('/characters/1/1/runeimbues');
        $this->assertGet('/characters/1/1/runeimbues/1');
        $this->assertGet('/characters/1/1/runeimbues/2', 404);
        $this->assertGet('/characters/1/2/runeimbues');
        $this->assertGet('/characters/1/2/runeimbues/1', 404);
        $this->assertGet('/characters/1/2/runeimbues/2', 404);
        $this->assertGet('/characters/1/99/runeimbues', 404);
        $this->assertGet('/characters/1/99/runeimbues/1', 404);
        $this->assertGet('/characters/1/99/runeimbues/2', 404);
        $this->assertGet('/characters/2/1/runeimbues', 403);
        $this->assertGet('/characters/2/1/runeimbues/1', 403);
        $this->assertGet('/characters/2/1/runeimbues/2', 403);
        $this->assertGet('/characters/99/1/runeimbues', 403);
        $this->assertGet('/characters/99/1/runeimbues/1', 403);
        $this->assertGet('/characters/99/1/runeimbues/2', 403);

        $this->withAuthReadOnly();
        $this->assertGet('/characters/1/1/runeimbues');
        $this->assertGet('/characters/1/1/runeimbues/1');
        $this->assertGet('/characters/1/1/runeimbues/2', 404);
        $this->assertGet('/characters/1/2/runeimbues');
        $this->assertGet('/characters/1/2/runeimbues/1', 404);
        $this->assertGet('/characters/1/2/runeimbues/2', 404);
        $this->assertGet('/characters/1/99/runeimbues', 404);
        $this->assertGet('/characters/1/99/runeimbues/1', 404);
        $this->assertGet('/characters/1/99/runeimbues/2', 404);
        $this->assertGet('/characters/2/1/runeimbues');
        $this->assertGet('/characters/2/1/runeimbues/1', 404);
        $this->assertGet('/characters/2/1/runeimbues/2', 404);
        $this->assertGet('/characters/99/1/runeimbues', 404);
        $this->assertGet('/characters/99/1/runeimbues/1', 404);
        $this->assertGet('/characters/99/1/runeimbues/2', 404);
    }

    public function testAuthorizationPut(): void
    {
        $this->withoutAuth();
        $this->assertPut('/characters/1/1/runeimbues', [], 401);
        $this->assertPut('/characters/1/1/runeimbues/1', [], 401);
        $this->assertPut('/characters/1/1/runeimbues/2', [], 401);
        $this->assertPut('/characters/1/2/runeimbues', [], 401);
        $this->assertPut('/characters/1/2/runeimbues/1', [], 401);
        $this->assertPut('/characters/1/2/runeimbues/2', [], 401);
        $this->assertPut('/characters/1/99/runeimbues', [], 401);
        $this->assertPut('/characters/1/99/runeimbues/1', [], 401);
        $this->assertPut('/characters/1/99/runeimbues/2', [], 401);
        $this->assertPut('/characters/2/1/runeimbues', [], 401);
        $this->assertPut('/characters/2/1/runeimbues/1', [], 401);
        $this->assertPut('/characters/2/1/runeimbues/2', [], 401);
        $this->assertPut('/characters/99/1/runeimbues', [], 401);
        $this->assertPut('/characters/99/1/runeimbues/1', [], 401);
        $this->assertPut('/characters/99/1/runeimbues/2', [], 401);

        $this->withAuthPlayer();
        $this->assertPut('/characters/1/1/runeimbues', [], 403);
        $this->assertPut('/characters/1/1/runeimbues/1', [], 403);
        $this->assertPut('/characters/1/1/runeimbues/2', [], 403);
        $this->assertPut('/characters/1/2/runeimbues', [], 403);
        $this->assertPut('/characters/1/2/runeimbues/1', [], 403);
        $this->assertPut('/characters/1/2/runeimbues/2', [], 403);
        $this->assertPut('/characters/1/99/runeimbues', [], 403);
        $this->assertPut('/characters/1/99/runeimbues/1', [], 403);
        $this->assertPut('/characters/1/99/runeimbues/2', [], 403);
        $this->assertPut('/characters/2/1/runeimbues', [], 403);
        $this->assertPut('/characters/2/1/runeimbues/1', [], 403);
        $this->assertPut('/characters/2/1/runeimbues/2', [], 403);
        $this->assertPut('/characters/99/1/runeimbues', [], 403);
        $this->assertPut('/characters/99/1/runeimbues/1', [], 403);
        $this->assertPut('/characters/99/1/runeimbues/2', [], 403);

        $this->withAuthReadOnly();
        $this->assertPut('/characters/1/1/runeimbues', [], 403);
        $this->assertPut('/characters/1/1/runeimbues/1', [], 403);
        $this->assertPut('/characters/1/1/runeimbues/2', [], 403);
        $this->assertPut('/characters/1/2/runeimbues', [], 403);
        $this->assertPut('/characters/1/2/runeimbues/1', [], 403);
        $this->assertPut('/characters/1/2/runeimbues/2', [], 403);
        $this->assertPut('/characters/1/99/runeimbues', [], 403);
        $this->assertPut('/characters/1/99/runeimbues/1', [], 403);
        $this->assertPut('/characters/1/99/runeimbues/2', [], 403);
        $this->assertPut('/characters/2/1/runeimbues', [], 403);
        $this->assertPut('/characters/2/1/runeimbues/1', [], 403);
        $this->assertPut('/characters/2/1/runeimbues/2', [], 403);
        $this->assertPut('/characters/99/1/runeimbues', [], 403);
        $this->assertPut('/characters/99/1/runeimbues/1', [], 403);
        $this->assertPut('/characters/99/1/runeimbues/2', [], 403);
    }

    public function testAuthorizationDelete(): void
    {
        $this->withoutAuth();
        $this->assertDelete('/characters/1/1/runeimbues/1', 401);
        $this->assertDelete('/characters/1/1/runeimbues/2', 401);
        $this->assertDelete('/characters/1/2/runeimbues/1', 401);
        $this->assertDelete('/characters/1/2/runeimbues/2', 401);
        $this->assertDelete('/characters/1/99/runeimbues/1', 401);
        $this->assertDelete('/characters/1/99/runeimbues/2', 401);
        $this->assertDelete('/characters/2/1/runeimbues/1', 401);
        $this->assertDelete('/characters/2/1/runeimbues/2', 401);
        $this->assertDelete('/characters/99/1/runeimbues/1', 401);
        $this->assertDelete('/characters/99/1/runeimbues/2', 401);

        $this->withAuthPlayer();
        $this->assertDelete('/characters/1/1/runeimbues/1', 403);
        $this->assertDelete('/characters/1/1/runeimbues/2', 403);
        $this->assertDelete('/characters/1/2/runeimbues/1', 403);
        $this->assertDelete('/characters/1/2/runeimbues/2', 403);
        $this->assertDelete('/characters/1/99/runeimbues/1', 403);
        $this->assertDelete('/characters/1/99/runeimbues/2', 403);
        $this->assertDelete('/characters/2/1/runeimbues/1', 403);
        $this->assertDelete('/characters/2/1/runeimbues/2', 403);
        $this->assertDelete('/characters/99/1/runeimbues/1', 403);
        $this->assertDelete('/characters/99/1/runeimbues/2', 403);

        $this->withAuthReadOnly();
        $this->assertDelete('/characters/1/1/runeimbues/1', 403);
        $this->assertDelete('/characters/1/1/runeimbues/2', 403);
        $this->assertDelete('/characters/1/2/runeimbues/1', 403);
        $this->assertDelete('/characters/1/2/runeimbues/2', 403);
        $this->assertDelete('/characters/1/99/runeimbues/1', 403);
        $this->assertDelete('/characters/1/99/runeimbues/2', 403);
        $this->assertDelete('/characters/2/1/runeimbues/1', 403);
        $this->assertDelete('/characters/2/1/runeimbues/2', 403);
        $this->assertDelete('/characters/99/1/runeimbues/1', 403);
        $this->assertDelete('/characters/99/1/runeimbues/2', 403);
    }

    public function testRequiredFieldsValidation(): void
    {
        $url = '/characters/1/1/runeimbues';

        $this->withAuthReferee();
        $response = $this->assertPut($url, [], 422);

        $errors = $this->assertErrorsResponse($url, $response);

        # expected fields with validation errors:
        $this->assertCount(1, $errors);
        $this->assertArrayHasKey('imbue_id', $errors);
    }

    public function testAddMinimal(): void
    {
        $input = [
# required fields:
            'imbue_id' => 2,
        ];

        $expected = [
            'class' => 'CharactersRuneImbue',
            'url' => '/characters/1/1/runeimbues/2',
            'times' => 1,
            'modifier_id' => TestAccount::Referee->value,
        ];

        $this->withAuthReferee();
        $actual = $this->assertPut('/characters/1/1/runeimbues', $input, 201);

        foreach ($expected as $key => $value) {
            $this->assertArrayKeyValue($key, $value, $actual);
        }
        $this->assertDateTimeNow($actual['modified']);
    }

    public function testAddComplete(): void
    {
        $input = [
# required fields:
            'imbue_id' => 2,
# optional fields:
            'times' => 4,
        ];

        $expected = [
            'class' => 'CharactersRuneImbue',
            'url' => '/characters/1/1/runeimbues/2',
            'times' => $input['times'],
            'modifier_id' => TestAccount::Referee->value,
        ];

        $this->withAuthReferee();
        $actual = $this->assertPut('/characters/1/1/runeimbues', $input, 201);

        foreach ($expected as $key => $value) {
            $this->assertArrayKeyValue($key, $value, $actual);
        }
        $this->assertDateTimeNow($actual['modified']);
    }

    public function testAddToConceptCharacter(): void
    {
        $input = [
            'imbue_id' => 2,
        ];

        $this->withAuthReferee();
        $actual = $this->assertPut('/characters/1/2/runeimbues', $input, 422);

        $errors = $this->assertErrorsResponse('/characters/1/2/runeimbues', $actual);
        # expected fields with validation errors:
        $this->assertCount(1, $errors);
        $this->assertArrayHasKey('character_id', $errors);
    }

    public function testEdit(): void
    {
        $input = [
# optional fields:
            'times' => 2,
        ];

        $expected = [
            'class' => 'CharactersRuneImbue',
            'url' => '/characters/1/1/runeimbues/1',
            'times' => $input['times'],
            'modifier_id' => TestAccount::Referee->value,
        ];

        $this->withAuthReferee();
        $actual = $this->assertPut('/characters/1/1/runeimbues/1', $input);

        foreach ($expected as $key => $value) {
            $this->assertArrayKeyValue($key, $value, $actual);
        }
        $this->assertDateTimeNow($actual['modified']);
    }

    public function testDelete(): void
    {
        $this->withAuthReferee();
        $this->assertGet('/characters/1/1/runeimbues/1');
        $this->assertDelete('/characters/1/1/runeimbues/1');
        $this->assertGet('/characters/1/1/runeimbues/1', 404);
    }
}
