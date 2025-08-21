<?php
declare(strict_types=1);

namespace App\Test\ApiTest;

use App\Test\Fixture\TestAccount;
use App\Test\TestSuite\AuthIntegrationTestCase;

class CharactersSkillsTest extends AuthIntegrationTestCase
{
    public function testAuthorizationGet(): void
    {
        $this->withoutAuth();
        $this->assertGet('/characters/1/1/skills', 401);
        $this->assertGet('/characters/1/1/skills/1', 401);
        $this->assertGet('/characters/1/1/skills/2', 401);
        $this->assertGet('/characters/1/2/skills', 401);
        $this->assertGet('/characters/1/2/skills/1', 401);
        $this->assertGet('/characters/1/2/skills/2', 401);
        $this->assertGet('/characters/2/1/skills', 401);
        $this->assertGet('/characters/2/1/skills/1', 401);
        $this->assertGet('/characters/2/1/skills/2', 401);
        $this->assertGet('/characters/99/1/skills', 401);
        $this->assertGet('/characters/99/1/skills/1', 401);
        $this->assertGet('/characters/99/1/skills/2', 401);

        $this->withAuthPlayer();
        $this->assertGet('/characters/1/1/skills');
        $this->assertGet('/characters/1/1/skills/1');
        $this->assertGet('/characters/1/1/skills/2', 404);
        $this->assertGet('/characters/1/2/skills', 404);
        $this->assertGet('/characters/1/2/skills/1', 404);
        $this->assertGet('/characters/1/2/skills/2', 404);
        $this->assertGet('/characters/2/1/skills', 403);
        $this->assertGet('/characters/2/1/skills/1', 403);
        $this->assertGet('/characters/2/1/skills/2', 403);
        $this->assertGet('/characters/99/1/skills', 403);
        $this->assertGet('/characters/99/1/skills/1', 403);
        $this->assertGet('/characters/99/1/skills/2', 403);

        $this->withAuthReadOnly();
        $this->assertGet('/characters/1/1/skills');
        $this->assertGet('/characters/1/1/skills/1');
        $this->assertGet('/characters/1/1/skills/2', 404);
        $this->assertGet('/characters/1/2/skills', 404);
        $this->assertGet('/characters/1/2/skills/1', 404);
        $this->assertGet('/characters/1/2/skills/2', 404);
        $this->assertGet('/characters/2/1/skills');
        $this->assertGet('/characters/2/1/skills/1', 404);
        $this->assertGet('/characters/2/1/skills/2');
        $this->assertGet('/characters/99/1/skills', 404);
        $this->assertGet('/characters/99/1/skills/1', 404);
        $this->assertGet('/characters/99/1/skills/2', 404);
    }

    public function testAuthorizationPut(): void
    {
        $this->withoutAuth();
        $this->assertPut('/characters/1/1/skills', [], 401);
        $this->assertPut('/characters/1/1/skills/1', [], 401);
        $this->assertPut('/characters/1/1/skills/2', [], 401);
        $this->assertPut('/characters/1/2/skills', [], 401);
        $this->assertPut('/characters/1/2/skills/1', [], 401);
        $this->assertPut('/characters/1/2/skills/2', [], 401);
        $this->assertPut('/characters/2/1/skills', [], 401);
        $this->assertPut('/characters/2/1/skills/1', [], 401);
        $this->assertPut('/characters/2/1/skills/2', [], 401);
        $this->assertPut('/characters/99/1/skills', [], 401);
        $this->assertPut('/characters/99/1/skills/1', [], 401);
        $this->assertPut('/characters/99/1/skills/2', [], 401);

        $this->withAuthPlayer();
        $this->assertPut('/characters/1/1/skills', [], 403);
        $this->assertPut('/characters/1/1/skills/1', [], 403);
        $this->assertPut('/characters/1/1/skills/2', [], 403);
        $this->assertPut('/characters/1/2/skills', [], 403);
        $this->assertPut('/characters/1/2/skills/1', [], 403);
        $this->assertPut('/characters/1/2/skills/2', [], 403);
        $this->assertPut('/characters/2/1/skills', [], 403);
        $this->assertPut('/characters/2/1/skills/1', [], 403);
        $this->assertPut('/characters/2/1/skills/2', [], 403);
        $this->assertPut('/characters/99/1/skills', [], 403);
        $this->assertPut('/characters/99/1/skills/1', [], 403);
        $this->assertPut('/characters/99/1/skills/2', [], 403);

        $this->withAuthReadOnly();
        $this->assertPut('/characters/1/1/skills', [], 403);
        $this->assertPut('/characters/1/1/skills/1', [], 403);
        $this->assertPut('/characters/1/1/skills/2', [], 403);
        $this->assertPut('/characters/1/2/skills', [], 403);
        $this->assertPut('/characters/1/2/skills/1', [], 403);
        $this->assertPut('/characters/1/2/skills/2', [], 403);
        $this->assertPut('/characters/2/1/skills', [], 403);
        $this->assertPut('/characters/2/1/skills/1', [], 403);
        $this->assertPut('/characters/2/1/skills/2', [], 403);
        $this->assertPut('/characters/99/1/skills', [], 403);
        $this->assertPut('/characters/99/1/skills/1', [], 403);
        $this->assertPut('/characters/99/1/skills/2', [], 403);
    }

    public function testAuthorizationDelete(): void
    {
        $this->withoutAuth();
        $this->assertDelete('/characters/1/1/skills/1', 401);
        $this->assertDelete('/characters/1/1/skills/2', 401);
        $this->assertDelete('/characters/1/2/skills/1', 401);
        $this->assertDelete('/characters/1/2/skills/2', 401);
        $this->assertDelete('/characters/2/1/skills/1', 401);
        $this->assertDelete('/characters/2/1/skills/2', 401);
        $this->assertDelete('/characters/99/1/skills/1', 401);
        $this->assertDelete('/characters/99/1/skills/2', 401);

        $this->withAuthPlayer();
        $this->assertDelete('/characters/1/1/skills/1', 403);
        $this->assertDelete('/characters/1/1/skills/2', 403);
        $this->assertDelete('/characters/1/2/skills/1', 403);
        $this->assertDelete('/characters/1/2/skills/2', 403);
        $this->assertDelete('/characters/2/1/skills/1', 403);
        $this->assertDelete('/characters/2/1/skills/2', 403);
        $this->assertDelete('/characters/99/1/skills/1', 403);
        $this->assertDelete('/characters/99/1/skills/2', 403);

        $this->withAuthReadOnly();
        $this->assertDelete('/characters/1/1/skills/1', 403);
        $this->assertDelete('/characters/1/1/skills/2', 403);
        $this->assertDelete('/characters/1/2/skills/1', 403);
        $this->assertDelete('/characters/1/2/skills/2', 403);
        $this->assertDelete('/characters/2/1/skills/1', 403);
        $this->assertDelete('/characters/2/1/skills/2', 403);
        $this->assertDelete('/characters/99/1/skills/1', 403);
        $this->assertDelete('/characters/99/1/skills/2', 403);
    }

    public function testRequiredFieldsValidation(): void
    {
        $url = '/characters/1/1/skills';

        $this->withAuthReferee();
        $response = $this->assertPut($url, [], 422);

        $errors = $this->assertErrorsResponse($url, $response);

        # expected fields with validation errors:
        $this->assertCount(1, $errors);
        $this->assertArrayHasKey('skill_id', $errors);
    }

    public function testAddMinimal(): void
    {
        $input = [
# required fields:
            'skill_id' => 2,
        ];

        $expected = [
            'class' => 'CharactersSkill',
            'url' => '/characters/1/1/skills/2',
            'times' => 1,
            'modifier_id' => TestAccount::Referee->value,
        ];

        $this->withAuthReferee();
        $actual = $this->assertPut('/characters/1/1/skills', $input, 201);

        foreach ($expected as $key => $value) {
            $this->assertArrayKeyValue($key, $value, $actual);
        }
        $this->assertDateTimeNow($actual['modified']);
    }

    public function testAddComplete(): void
    {
        $input = [
# required fields:
            'skill_id' => 2,
            'times' => 3,
        ];

        $expected = [
            'class' => 'CharactersSkill',
            'url' => '/characters/1/1/skills/2',
            'times' => $input['times'],
            'modifier_id' => TestAccount::Referee->value,
        ];

        $this->withAuthReferee();
        $actual = $this->assertPut('/characters/1/1/skills', $input, 201);

        foreach ($expected as $key => $value) {
            $this->assertArrayKeyValue($key, $value, $actual);
        }
        $this->assertDateTimeNow($actual['modified']);
    }

    public function testEdit(): void
    {
        $input = [
# optional fields:
            'times' => 2,
        ];

        $expected = [
            'class' => 'CharactersSkill',
            'url' => '/characters/1/1/skills/1',
            'times' => $input['times'],
            'modifier_id' => TestAccount::Referee->value,
        ];

        $this->withAuthReferee();
        $actual = $this->assertPut('/characters/1/1/skills/1', $input);

        foreach ($expected as $key => $value) {
            $this->assertArrayKeyValue($key, $value, $actual);
        }
        $this->assertDateTimeNow($actual['modified']);
    }

    public function testDelete(): void
    {
        $this->withAuthReferee();
        $this->assertGet('/characters/1/1/skills/1');
        $this->assertDelete('/characters/1/1/skills/1');
        $this->assertGet('/characters/1/1/skills/1', 404);
    }
}
