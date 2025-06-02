<?php
declare(strict_types=1);

namespace App\Test\TestCase\Integration;

use App\Test\Fixture\TestAccount;
use App\Test\TestSuite\AuthIntegrationTestCase;

class CharactersConditionsTest extends AuthIntegrationTestCase
{
    public function testAuthorizationGet()
    {
        $this->withoutAuth();
        $this->assertGet('/characters/1/1/conditions', 401);
        $this->assertGet('/characters/1/1/conditions/1', 401);
        $this->assertGet('/characters/1/1/conditions/2', 401);
        $this->assertGet('/characters/1/2/conditions', 401);
        $this->assertGet('/characters/1/2/conditions/1', 401);
        $this->assertGet('/characters/1/2/conditions/2', 401);
        $this->assertGet('/characters/2/1/conditions', 401);
        $this->assertGet('/characters/2/1/conditions/1', 401);
        $this->assertGet('/characters/2/1/conditions/2', 401);
        $this->assertGet('/characters/2/2/conditions', 401);
        $this->assertGet('/characters/2/2/conditions/1', 401);
        $this->assertGet('/characters/2/2/conditions/2', 401);
        $this->assertGet('/characters/99/1/conditions', 401);
        $this->assertGet('/characters/99/1/conditions/1', 401);
        $this->assertGet('/characters/99/1/conditions/2', 401);

        $this->withAuthPlayer();
        $this->assertGet('/characters/1/1/conditions');
        $this->assertGet('/characters/1/1/conditions/1');
        $this->assertGet('/characters/1/1/conditions/2', 404);
        $this->assertGet('/characters/1/2/conditions', 404);
        $this->assertGet('/characters/1/2/conditions/1', 404);
        $this->assertGet('/characters/1/2/conditions/2', 404);
        $this->assertGet('/characters/2/1/conditions', 403);
        $this->assertGet('/characters/2/1/conditions/1', 403);
        $this->assertGet('/characters/2/1/conditions/2', 403);
        $this->assertGet('/characters/99/1/conditions', 403);
        $this->assertGet('/characters/99/1/conditions/1', 403);
        $this->assertGet('/characters/99/1/conditions/2', 403);

        $this->withAuthReadOnly();
        $this->assertGet('/characters/1/1/conditions');
        $this->assertGet('/characters/1/1/conditions/1');
        $this->assertGet('/characters/1/1/conditions/2', 404);
        $this->assertGet('/characters/1/2/conditions', 404);
        $this->assertGet('/characters/1/2/conditions/1', 404);
        $this->assertGet('/characters/1/2/conditions/2', 404);
        $this->assertGet('/characters/2/1/conditions');
        $this->assertGet('/characters/2/1/conditions/1');
        $this->assertGet('/characters/2/1/conditions/2');
        $this->assertGet('/characters/99/1/conditions', 404);
        $this->assertGet('/characters/99/1/conditions/1', 404);
        $this->assertGet('/characters/99/1/conditions/2', 404);
    }

    public function testAuthorizationPut()
    {
        $this->withoutAuth();
        $this->assertPut('/characters/1/1/conditions', [], 401);
        $this->assertPut('/characters/1/1/conditions/1', [], 401);
        $this->assertPut('/characters/1/1/conditions/2', [], 401);
        $this->assertPut('/characters/1/2/conditions', [], 401);
        $this->assertPut('/characters/1/2/conditions/1', [], 401);
        $this->assertPut('/characters/1/2/conditions/2', [], 401);
        $this->assertPut('/characters/2/1/conditions', [], 401);
        $this->assertPut('/characters/2/1/conditions/1', [], 401);
        $this->assertPut('/characters/2/1/conditions/2', [], 401);
        $this->assertPut('/characters/99/1/conditions', [], 401);
        $this->assertPut('/characters/99/1/conditions/1', [], 401);
        $this->assertPut('/characters/99/1/conditions/2', [], 401);

        $this->withAuthPlayer();
        $this->assertPut('/characters/1/1/conditions', [], 403);
        $this->assertPut('/characters/1/1/conditions/1', [], 403);
        $this->assertPut('/characters/1/1/conditions/2', [], 403);
        $this->assertPut('/characters/1/2/conditions', [], 403);
        $this->assertPut('/characters/1/2/conditions/1', [], 403);
        $this->assertPut('/characters/1/2/conditions/2', [], 403);
        $this->assertPut('/characters/2/1/conditions', [], 403);
        $this->assertPut('/characters/2/1/conditions/1', [], 403);
        $this->assertPut('/characters/2/1/conditions/2', [], 403);
        $this->assertPut('/characters/99/1/conditions', [], 403);
        $this->assertPut('/characters/99/1/conditions/1', [], 403);
        $this->assertPut('/characters/99/1/conditions/2', [], 403);

        $this->withAuthReadOnly();
        $this->assertPut('/characters/1/1/conditions', [], 403);
        $this->assertPut('/characters/1/1/conditions/1', [], 403);
        $this->assertPut('/characters/1/1/conditions/2', [], 403);
        $this->assertPut('/characters/1/2/conditions', [], 403);
        $this->assertPut('/characters/1/2/conditions/1', [], 403);
        $this->assertPut('/characters/1/2/conditions/2', [], 403);
        $this->assertPut('/characters/2/1/conditions', [], 403);
        $this->assertPut('/characters/2/1/conditions/1', [], 403);
        $this->assertPut('/characters/2/1/conditions/2', [], 403);
        $this->assertPut('/characters/99/1/conditions', [], 403);
        $this->assertPut('/characters/99/1/conditions/1', [], 403);
        $this->assertPut('/characters/99/1/conditions/2', [], 403);
    }

    public function testAuthorizationDelete()
    {
        $this->withoutAuth();
        $this->assertDelete('/characters/1/1/conditions/1', 401);
        $this->assertDelete('/characters/1/1/conditions/2', 401);
        $this->assertDelete('/characters/1/2/conditions/1', 401);
        $this->assertDelete('/characters/1/2/conditions/2', 401);
        $this->assertDelete('/characters/2/1/conditions/1', 401);
        $this->assertDelete('/characters/2/1/conditions/2', 401);
        $this->assertDelete('/characters/99/1/conditions/1', 401);
        $this->assertDelete('/characters/99/1/conditions/2', 401);

        $this->withAuthPlayer();
        $this->assertDelete('/characters/1/1/conditions/1', 403);
        $this->assertDelete('/characters/1/1/conditions/2', 403);
        $this->assertDelete('/characters/1/2/conditions/1', 403);
        $this->assertDelete('/characters/1/2/conditions/2', 403);
        $this->assertDelete('/characters/2/1/conditions/1', 403);
        $this->assertDelete('/characters/2/1/conditions/2', 403);
        $this->assertDelete('/characters/99/1/conditions/1', 403);
        $this->assertDelete('/characters/99/1/conditions/2', 403);

        $this->withAuthReadOnly();
        $this->assertDelete('/characters/1/1/conditions/1', 403);
        $this->assertDelete('/characters/1/1/conditions/2', 403);
        $this->assertDelete('/characters/1/2/conditions/1', 403);
        $this->assertDelete('/characters/1/2/conditions/2', 403);
        $this->assertDelete('/characters/2/1/conditions/1', 403);
        $this->assertDelete('/characters/2/1/conditions/2', 403);
        $this->assertDelete('/characters/99/1/conditions/1', 403);
        $this->assertDelete('/characters/99/1/conditions/2', 403);
    }

    public function testRequiredFieldsValidation()
    {
        $url = '/characters/1/1/conditions';

        $this->withAuthReferee();
        $response = $this->assertPut($url, [], 422);

        $errors = $this->assertErrorsResponse($url, $response);

        # expected fields with validation errors:
        $this->assertCount(1, $errors);
        $this->assertArrayHasKey('condition_id', $errors);
    }

    public function testAddCharactersConditionMinimal()
    {
        $input = [
# required fields:
            'condition_id' => 2,
        ];

        $expected = [
            'class' => 'CharactersCondition',
            'url' => '/characters/1/1/conditions/2',
            'expiry' => null,
            'modifier_id' => TestAccount::Referee->value,
        ];

        $this->withAuthReferee();
        $actual = $this->assertPut('/characters/1/1/conditions', $input, 201);

        foreach ($expected as $key => $value) {
            $this->assertArrayKeyValue($key, $value, $actual);
        }
        $this->assertDateTimeNow($actual['modified']);
    }

    public function testAddCharactersConditionComplete()
    {
        $input = [
# required fields:
            'condition_id' => 2,
            'expiry' => '2025-05-29',
        ];

        $expected = [
            'class' => 'CharactersCondition',
            'url' => '/characters/1/1/conditions/2',
            'expiry' => $input['expiry'],
            'modifier_id' => TestAccount::Referee->value,
        ];

        $this->withAuthReferee();
        $actual = $this->assertPut('/characters/1/1/conditions', $input, 201);

        foreach ($expected as $key => $value) {
            $this->assertArrayKeyValue($key, $value, $actual);
        }
        $this->assertDateTimeNow($actual['modified']);
    }

    public function testEditCharactersCondition()
    {
        $input = [
# optional fields:
            'expiry' => '2025-05-29',
        ];

        $expected = [
            'class' => 'CharactersCondition',
            'url' => '/characters/1/1/conditions/1',
            'expiry' => $input['expiry'],
            'modifier_id' => TestAccount::Referee->value,
        ];

        $this->withAuthReferee();
        $actual = $this->assertPut('/characters/1/1/conditions/1', $input);

        foreach ($expected as $key => $value) {
            $this->assertArrayKeyValue($key, $value, $actual);
        }
        $this->assertDateTimeNow($actual['modified']);
    }

    public function testDeleteCharacterSkill()
    {
        $this->withAuthReferee();
        $this->assertGet('/characters/1/1/conditions/1');
        $this->assertDelete('/characters/1/1/conditions/1');
        $this->assertGet('/characters/1/1/conditions/1', 404);
    }
}
