<?php
declare(strict_types=1);

namespace App\Test\ApiTest;

use App\Test\Fixture\TestAccount;
use App\Test\TestSuite\AuthIntegrationTestCase;

class CharactersGlyphImbuesTest extends AuthIntegrationTestCase
{
    public function testAuthorizationGet(): void
    {
        $this->withoutAuth();
        $this->assertGet('/characters/1/1/glyphimbues', 401);
        $this->assertGet('/characters/1/1/glyphimbues/1', 401);
        $this->assertGet('/characters/1/1/glyphimbues/2', 401);
        $this->assertGet('/characters/1/2/glyphimbues', 401);
        $this->assertGet('/characters/1/2/glyphimbues/1', 401);
        $this->assertGet('/characters/1/2/glyphimbues/2', 401);
        $this->assertGet('/characters/1/99/glyphimbues', 401);
        $this->assertGet('/characters/1/99/glyphimbues/1', 401);
        $this->assertGet('/characters/1/99/glyphimbues/2', 401);
        $this->assertGet('/characters/2/1/glyphimbues', 401);
        $this->assertGet('/characters/2/1/glyphimbues/1', 401);
        $this->assertGet('/characters/2/1/glyphimbues/2', 401);
        $this->assertGet('/characters/2/2/glyphimbues', 401);
        $this->assertGet('/characters/2/2/glyphimbues/1', 401);
        $this->assertGet('/characters/2/2/glyphimbues/2', 401);
        $this->assertGet('/characters/99/1/glyphimbues', 401);
        $this->assertGet('/characters/99/1/glyphimbues/1', 401);
        $this->assertGet('/characters/99/1/glyphimbues/2', 401);

        $this->withAuthPlayer();
        $this->assertGet('/characters/1/1/glyphimbues');
        $this->assertGet('/characters/1/1/glyphimbues/1');
        $this->assertGet('/characters/1/1/glyphimbues/2', 404);
        $this->assertGet('/characters/1/2/glyphimbues');
        $this->assertGet('/characters/1/2/glyphimbues/1', 404);
        $this->assertGet('/characters/1/2/glyphimbues/2', 404);
        $this->assertGet('/characters/1/99/glyphimbues', 404);
        $this->assertGet('/characters/1/99/glyphimbues/1', 404);
        $this->assertGet('/characters/1/99/glyphimbues/2', 404);
        $this->assertGet('/characters/2/1/glyphimbues', 403);
        $this->assertGet('/characters/2/1/glyphimbues/1', 403);
        $this->assertGet('/characters/2/1/glyphimbues/2', 403);
        $this->assertGet('/characters/99/1/glyphimbues', 403);
        $this->assertGet('/characters/99/1/glyphimbues/1', 403);
        $this->assertGet('/characters/99/1/glyphimbues/2', 403);

        $this->withAuthReadOnly();
        $this->assertGet('/characters/1/1/glyphimbues');
        $this->assertGet('/characters/1/1/glyphimbues/1');
        $this->assertGet('/characters/1/1/glyphimbues/2', 404);
        $this->assertGet('/characters/1/2/glyphimbues');
        $this->assertGet('/characters/1/2/glyphimbues/1', 404);
        $this->assertGet('/characters/1/2/glyphimbues/2', 404);
        $this->assertGet('/characters/1/99/glyphimbues', 404);
        $this->assertGet('/characters/1/99/glyphimbues/1', 404);
        $this->assertGet('/characters/1/99/glyphimbues/2', 404);
        $this->assertGet('/characters/2/1/glyphimbues');
        $this->assertGet('/characters/2/1/glyphimbues/1', 404);
        $this->assertGet('/characters/2/1/glyphimbues/2');
        $this->assertGet('/characters/99/1/glyphimbues', 404);
        $this->assertGet('/characters/99/1/glyphimbues/1', 404);
        $this->assertGet('/characters/99/1/glyphimbues/2', 404);
    }

    public function testAuthorizationPut(): void
    {
        $this->withoutAuth();
        $this->assertPut('/characters/1/1/glyphimbues', [], 401);
        $this->assertPut('/characters/1/1/glyphimbues/1', [], 401);
        $this->assertPut('/characters/1/1/glyphimbues/2', [], 401);
        $this->assertPut('/characters/1/2/glyphimbues', [], 401);
        $this->assertPut('/characters/1/2/glyphimbues/1', [], 401);
        $this->assertPut('/characters/1/2/glyphimbues/2', [], 401);
        $this->assertPut('/characters/1/99/glyphimbues', [], 401);
        $this->assertPut('/characters/1/99/glyphimbues/1', [], 401);
        $this->assertPut('/characters/1/99/glyphimbues/2', [], 401);
        $this->assertPut('/characters/2/1/glyphimbues', [], 401);
        $this->assertPut('/characters/2/1/glyphimbues/1', [], 401);
        $this->assertPut('/characters/2/1/glyphimbues/2', [], 401);
        $this->assertPut('/characters/99/1/glyphimbues', [], 401);
        $this->assertPut('/characters/99/1/glyphimbues/1', [], 401);
        $this->assertPut('/characters/99/1/glyphimbues/2', [], 401);

        $this->withAuthPlayer();
        $this->assertPut('/characters/1/1/glyphimbues', [], 403);
        $this->assertPut('/characters/1/1/glyphimbues/1', [], 403);
        $this->assertPut('/characters/1/1/glyphimbues/2', [], 403);
        $this->assertPut('/characters/1/2/glyphimbues', [], 403);
        $this->assertPut('/characters/1/2/glyphimbues/1', [], 403);
        $this->assertPut('/characters/1/2/glyphimbues/2', [], 403);
        $this->assertPut('/characters/1/99/glyphimbues', [], 403);
        $this->assertPut('/characters/1/99/glyphimbues/1', [], 403);
        $this->assertPut('/characters/1/99/glyphimbues/2', [], 403);
        $this->assertPut('/characters/2/1/glyphimbues', [], 403);
        $this->assertPut('/characters/2/1/glyphimbues/1', [], 403);
        $this->assertPut('/characters/2/1/glyphimbues/2', [], 403);
        $this->assertPut('/characters/99/1/glyphimbues', [], 403);
        $this->assertPut('/characters/99/1/glyphimbues/1', [], 403);
        $this->assertPut('/characters/99/1/glyphimbues/2', [], 403);

        $this->withAuthReadOnly();
        $this->assertPut('/characters/1/1/glyphimbues', [], 403);
        $this->assertPut('/characters/1/1/glyphimbues/1', [], 403);
        $this->assertPut('/characters/1/1/glyphimbues/2', [], 403);
        $this->assertPut('/characters/1/2/glyphimbues', [], 403);
        $this->assertPut('/characters/1/2/glyphimbues/1', [], 403);
        $this->assertPut('/characters/1/2/glyphimbues/2', [], 403);
        $this->assertPut('/characters/1/99/glyphimbues', [], 403);
        $this->assertPut('/characters/1/99/glyphimbues/1', [], 403);
        $this->assertPut('/characters/1/99/glyphimbues/2', [], 403);
        $this->assertPut('/characters/2/1/glyphimbues', [], 403);
        $this->assertPut('/characters/2/1/glyphimbues/1', [], 403);
        $this->assertPut('/characters/2/1/glyphimbues/2', [], 403);
        $this->assertPut('/characters/99/1/glyphimbues', [], 403);
        $this->assertPut('/characters/99/1/glyphimbues/1', [], 403);
        $this->assertPut('/characters/99/1/glyphimbues/2', [], 403);
    }

    public function testAuthorizationDelete(): void
    {
        $this->withoutAuth();
        $this->assertDelete('/characters/1/1/glyphimbues/1', 401);
        $this->assertDelete('/characters/1/1/glyphimbues/2', 401);
        $this->assertDelete('/characters/1/2/glyphimbues/1', 401);
        $this->assertDelete('/characters/1/2/glyphimbues/2', 401);
        $this->assertDelete('/characters/1/99/glyphimbues/1', 401);
        $this->assertDelete('/characters/1/99/glyphimbues/2', 401);
        $this->assertDelete('/characters/2/1/glyphimbues/1', 401);
        $this->assertDelete('/characters/2/1/glyphimbues/2', 401);
        $this->assertDelete('/characters/99/1/glyphimbues/1', 401);
        $this->assertDelete('/characters/99/1/glyphimbues/2', 401);

        $this->withAuthPlayer();
        $this->assertDelete('/characters/1/1/glyphimbues/1', 403);
        $this->assertDelete('/characters/1/1/glyphimbues/2', 403);
        $this->assertDelete('/characters/1/2/glyphimbues/1', 403);
        $this->assertDelete('/characters/1/2/glyphimbues/2', 403);
        $this->assertDelete('/characters/1/99/glyphimbues/1', 403);
        $this->assertDelete('/characters/1/99/glyphimbues/2', 403);
        $this->assertDelete('/characters/2/1/glyphimbues/1', 403);
        $this->assertDelete('/characters/2/1/glyphimbues/2', 403);
        $this->assertDelete('/characters/99/1/glyphimbues/1', 403);
        $this->assertDelete('/characters/99/1/glyphimbues/2', 403);

        $this->withAuthReadOnly();
        $this->assertDelete('/characters/1/1/glyphimbues/1', 403);
        $this->assertDelete('/characters/1/1/glyphimbues/2', 403);
        $this->assertDelete('/characters/1/2/glyphimbues/1', 403);
        $this->assertDelete('/characters/1/2/glyphimbues/2', 403);
        $this->assertDelete('/characters/1/99/glyphimbues/1', 403);
        $this->assertDelete('/characters/1/99/glyphimbues/2', 403);
        $this->assertDelete('/characters/2/1/glyphimbues/1', 403);
        $this->assertDelete('/characters/2/1/glyphimbues/2', 403);
        $this->assertDelete('/characters/99/1/glyphimbues/1', 403);
        $this->assertDelete('/characters/99/1/glyphimbues/2', 403);
    }

    public function testRequiredFieldsValidation(): void
    {
        $url = '/characters/1/1/glyphimbues';

        $this->withAuthReferee();
        $errors = $this->assertValidationError($url, []);

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
            'class' => 'CharactersGlyphImbue',
            'url' => '/characters/1/1/glyphimbues/2',
            'times' => 1,
            'modifier_id' => TestAccount::Referee->value,
        ];

        $this->withAuthReferee();
        $actual = $this->assertPut('/characters/1/1/glyphimbues', $input, 201);

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
            'class' => 'CharactersGlyphImbue',
            'url' => '/characters/1/1/glyphimbues/2',
            'times' => $input['times'],
            'modifier_id' => TestAccount::Referee->value,
        ];

        $this->withAuthReferee();
        $actual = $this->assertPut('/characters/1/1/glyphimbues', $input, 201);

        foreach ($expected as $key => $value) {
            $this->assertArrayKeyValue($key, $value, $actual);
        }
        $this->assertDateTimeNow($actual['modified']);
    }

    public function testAddNoDeprecated(): void
    {
        $input = [
            'imbue_id' => 3,
        ];

        $this->withAuthReferee();
        $errors = $this->assertValidationError('/characters/1/1/glyphimbues', $input);

        # expected fields with validation errors:
        $this->assertCount(1, $errors);
        $this->assertArrayHasKey('imbue_id', $errors);
    }

    public function testAddToConceptCharacter(): void
    {
        $input = [
            'imbue_id' => 2,
        ];

        $this->withAuthReferee();
        $errors = $this->assertValidationError('/characters/1/2/glyphimbues', $input);

        # expected fields with validation errors:
        $this->assertCount(1, $errors);
        $this->assertArrayHasKey('character_id', $errors);
    }

    public function testEdit(): void
    {
        $input = [
# optional fields:
            'times' => 1,
        ];

        $expected = [
            'class' => 'CharactersGlyphImbue',
            'url' => '/characters/2/1/glyphimbues/2',
            'times' => $input['times'],
            'modifier_id' => TestAccount::Referee->value,
        ];

        $this->withAuthReferee();
        $actual = $this->assertPut('/characters/2/1/glyphimbues/2', $input);

        foreach ($expected as $key => $value) {
            $this->assertArrayKeyValue($key, $value, $actual);
        }
        $this->assertDateTimeNow($actual['modified']);
    }

    public function testEditTimesLimit(): void
    {
        $input = [
# optional fields:
            'times' => 9999,
        ];

        $this->withAuthReferee();
        $errors = $this->assertValidationError('/characters/1/1/glyphimbues/1', $input);

        # expected fields with validation errors:
        $this->assertCount(1, $errors);
        $this->assertArrayHasKey('times', $errors);
    }

    public function testDelete(): void
    {
        $this->withAuthReferee();
        $this->assertGet('/characters/2/1/glyphimbues/2');
        $this->assertDelete('/characters/2/1/glyphimbues/2');
        $this->assertGet('/characters/2/1/glyphimbues/2', 404);
    }
}
