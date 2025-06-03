<?php
declare(strict_types=1);

namespace App\Test\ApiTest;

use App\Test\Fixture\TestAccount;
use App\Test\TestSuite\AuthIntegrationTestCase;

class ConditionsTest extends AuthIntegrationTestCase
{
    public function testAuthorization(): void
    {
        $this->withoutAuth();
        $this->assertGet('/conditions', 401);
        $this->assertGet('/conditions/1', 401);
        $this->assertGet('/conditions/1/characters', 401);
        $this->assertGet('/conditions/2', 401);
        $this->assertGet('/conditions/2/characters', 401);
        $this->assertGet('/conditions/99', 401);
        $this->assertGet('/conditions/99/characters', 401);
        $this->assertPut('/conditions', [], 401);
        $this->assertPut('/conditions/1', [], 401);
        $this->assertPut('/conditions/2', [], 401);
        $this->assertPut('/conditions/99', [], 401);

        $this->withAuthPlayer();
        $this->assertGet('/conditions');
        $this->assertGet('/conditions/1');
        $this->assertGet('/conditions/1/characters', 403);
        $this->assertGet('/conditions/2', 403);
        $this->assertGet('/conditions/2/characters', 403);
        $this->assertGet('/conditions/99', 404);
        $this->assertGet('/conditions/99/characters', 403);
        $this->assertPut('/conditions', [], 403);
        $this->assertPut('/conditions/1', [], 403);
        $this->assertPut('/conditions/2', [], 403);
        $this->assertPut('/conditions/99', [], 403);

        $this->withAuthReadOnly();
        $this->assertGet('/conditions');
        $this->assertGet('/conditions/1');
        $this->assertGet('/conditions/1/characters');
        $this->assertGet('/conditions/2');
        $this->assertGet('/conditions/2/characters');
        $this->assertGet('/conditions/99', 404);
        $this->assertGet('/conditions/99/characters', 404);
        $this->assertPut('/conditions', [], 403);
        $this->assertPut('/conditions/1', [], 403);
        $this->assertPut('/conditions/2', [], 403);
        $this->assertPut('/conditions/99', [], 403);

        $this->withAuthReferee();
        $this->assertGet('/conditions');
        $this->assertGet('/conditions/1');
        $this->assertGet('/conditions/1/characters');
        $this->assertGet('/conditions/2');
        $this->assertGet('/conditions/2/characters');
        $this->assertGet('/conditions/99', 404);
        $this->assertGet('/conditions/99/characters', 404);
        $this->assertPut('/conditions', [], 422);
        $this->assertPut('/conditions/1', []);
        $this->assertPut('/conditions/2', []);
        $this->assertPut('/conditions/99', [], 404);
    }

    public function testAddConditionMinimal(): void
    {
        $input = [
# required fields:
            'name' => 'condition name',
            'player_text' => 'player explenation',
        ];

        $expected = [
            'class' => 'Condition',
            'url' => '/conditions/3',
            'coin' => 3,
            'name' => $input['name'],
            'player_text' => $input['player_text'],
            'referee_notes' => null,
            'notes' => null,
            'deprecated' => false,
            'modifier_id' => TestAccount::Referee->value,
            'creator_id' => TestAccount::Referee->value,
        ];

        $this->withAuthReferee();
        $actual = $this->assertPut('/conditions', $input, 201);

        foreach ($expected as $key => $value) {
            $this->assertArrayKeyValue($key, $value, $actual);
        }
        $this->assertDateTimeNow($actual['modified']);
        $this->assertDateTimeNow($actual['created']);
    }

    public function testAddConditionComplete(): void
    {
        $input = [
# required fields:
            'name' => 'condition name',
            'player_text' => 'player explenation',
# optional fields:
            'referee_notes' => 'hidden referee details',
            'notes' => 'infobalie notes',
            'deprecated' => true,
# ignored fields:
            'coin' => 66,
            'modifier_id' => 9,
            'creator_id' => 9,
            'ignored' => 'ignored',
        ];

        $expected = [
            'class' => 'Condition',
            'url' => '/conditions/3',
            'coin' => 3,
            'name' => $input['name'],
            'player_text' => $input['player_text'],
            'referee_notes' => $input['referee_notes'],
            'notes' => $input['notes'],
            'deprecated' => $input['deprecated'],
            'modifier_id' => TestAccount::Referee->value,
            'creator_id' => TestAccount::Referee->value,
        ];

        $this->withAuthReferee();
        $actual = $this->assertPut('/conditions', $input, 201);

        foreach ($expected as $key => $value) {
            $this->assertArrayKeyValue($key, $value, $actual);
        }
        $this->assertDateTimeNow($actual['modified']);
        $this->assertDateTimeNow($actual['created']);

        $this->assertArrayNotHasKey('ignored', $actual);
    }

    public function testRequiredFieldsValidation(): void
    {
        $this->withAuthReferee();
        $response = $this->assertPut('/conditions', [], 422);

        $errors = $this->assertErrorsResponse('/conditions', $response);

        # expected fields with validation errors:
        $this->assertCount(2, $errors);
        $this->assertArrayHasKey('name', $errors);
        $this->assertArrayHasKey('player_text', $errors);
    }
}
