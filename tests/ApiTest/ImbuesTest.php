<?php
declare(strict_types=1);

namespace App\Test\ApiTest;

use App\Test\Fixture\TestAccount;
use App\Test\TestSuite\AuthIntegrationTestCase;

class ImbuesTest extends AuthIntegrationTestCase
{
    public function testAuthorizationGet(): void
    {
        $this->withoutAuth();
        $this->assertGet('/imbues', 401);
        $this->assertGet('/imbues/1', 401);
        $this->assertGet('/imbues/1/characters', 401);
        $this->assertGet('/imbues/99', 401);
        $this->assertGet('/imbues/99/characters', 401);

        $this->withAuthPlayer();
        $this->assertGet('/imbues');
        $this->assertGet('/imbues/1');
        $this->assertGet('/imbues/1/characters', 403);
        $this->assertGet('/imbues/99', 404);
        $this->assertGet('/imbues/99/characters', 403);

        $this->withAuthReadOnly();
        $this->assertGet('/imbues');
        $this->assertGet('/imbues/1');
        $this->assertGet('/imbues/1/characters');
        $this->assertGet('/imbues/99', 404);
        $this->assertGet('/imbues/99/characters', 404);

        $this->withAuthReferee();
        $this->assertGet('/imbues');
        $this->assertGet('/imbues/1');
        $this->assertGet('/imbues/1/characters');
        $this->assertGet('/imbues/99', 404);
        $this->assertGet('/imbues/99/characters', 404);

        $this->withAuthInfobalie();
        $this->assertGet('/imbues');
        $this->assertGet('/imbues/1');
        $this->assertGet('/imbues/1/characters');
        $this->assertGet('/imbues/99', 404);
        $this->assertGet('/imbues/99/characters', 404);
    }

    public function testAuthorizationPut(): void
    {
        $this->withoutAuth();
        $this->assertPut('/imbues', [], 401);
        $this->assertPut('/imbues/1', [], 401);
        $this->assertPut('/imbues/99', [], 401);

        $this->withAuthPlayer();
        $this->assertPut('/imbues', [], 403);
        $this->assertPut('/imbues/1', [], 403);
        $this->assertPut('/imbues/99', [], 403);

        $this->withAuthReadOnly();
        $this->assertPut('/imbues', [], 403);
        $this->assertPut('/imbues/1', [], 403);
        $this->assertPut('/imbues/99', [], 403);

        $this->withAuthReferee();
        $this->assertPut('/imbues', [], 422);
        $this->assertPut('/imbues/1', []);
        $this->assertPut('/imbues/99', [], 404);

        $this->withAuthInfobalie();
        $this->assertPut('/imbues', [], 422);
        $this->assertPut('/imbues/1', []);
        $this->assertPut('/imbues/99', [], 404);
    }

    public function testAuthorizationDelete(): void
    {
        $this->withoutAuth();
        $this->assertDelete('/imbues/1', 401);
        $this->assertDelete('/imbues/99', 401);

        $this->withAuthPlayer();
        $this->assertDelete('/imbues/1', 403);
        $this->assertDelete('/imbues/99', 403);

        $this->withAuthReadOnly();
        $this->assertDelete('/imbues/1', 403);
        $this->assertDelete('/imbues/99', 403);

        $this->withAuthReferee();
        $this->assertDelete('/imbues/1', 403);
        $this->assertDelete('/imbues/99', 403);

        $this->withAuthInfobalie();
        $this->assertDelete('/imbues/1', 403);
        $this->assertDelete('/imbues/99', 403);
    }

    public function testAddMinimal(): void
    {
        $input = [
# required fields:
            'name' => 'recipe name',
            'cost' => 6,
            'description' => 'player explenation',
        ];

        $expected = [
            'class' => 'Imbue',
            'url' => '/imbues/4',
            'id' => 4,
            'name' => $input['name'],
            'description' => $input['description'],
            'notes' => null,
            'times_max' => 1,
            'deprecated' => false,
            'modifier_id' => TestAccount::Referee->value,
        ];

        $this->withAuthReferee();
        $actual = $this->assertPut('/imbues', $input, 201);

        foreach ($expected as $key => $value) {
            $this->assertArrayKeyValue($key, $value, $actual);
        }
        $this->assertDateTimeNow($actual['modified']);
    }

    public function testAddComplete(): void
    {
        $input = [
# required fields:
            'name' => 'recipe name',
            'cost' => 6,
            'description' => 'player explenation',
# optional fields:
            'notes' => 'infobalie notes',
            'deprecated' => true,
# ignored fields:
            'modifier_id' => 9,
            'ignored' => 'ignored',
        ];

        $expected = [
            'class' => 'Imbue',
            'url' => '/imbues/4',
            'id' => 4,
            'name' => $input['name'],
            'description' => $input['description'],
            'notes' => $input['notes'],
            'deprecated' => $input['deprecated'],
            'modifier_id' => TestAccount::Referee->value,
        ];

        $this->withAuthReferee();
        $actual = $this->assertPut('/imbues', $input, 201);

        foreach ($expected as $key => $value) {
            $this->assertArrayKeyValue($key, $value, $actual);
        }
        $this->assertDateTimeNow($actual['modified']);

        $this->assertArrayNotHasKey('ignored', $actual);
    }

    public function testAddValidation(): void
    {
        $input = [
# disallowed fields:
            'id' => 55,
# required fields, not allowed empty
            'name' => '',
            'description' => '',
        ];

        $this->withAuthReferee();
        $response = $this->assertPut('/imbues', $input, 422);

        $errors = $this->assertErrorsResponse('/imbues', $response);

        # expected fields with validation errors:
        $this->assertCount(3, $errors);
        $this->assertArrayHasKey('name', $errors);
        $this->assertArrayHasKey('cost', $errors);
        $this->assertArrayHasKey('description', $errors);
    }

    public function testEditValidation(): void
    {
        $input = [
# disallowed fields:
            'id' => 55,
# required fields, not allowed empty
            'name' => '',
            'description' => '',
        ];

        $this->withAuthReferee();
        $response = $this->assertPut('/imbues/1', $input, 422);

        $errors = $this->assertErrorsResponse('/imbues/1', $response);

        # expected fields with validation errors:
        $this->assertCount(2, $errors);
        $this->assertArrayHasKey('name', $errors);
        $this->assertArrayHasKey('description', $errors);
    }
}
