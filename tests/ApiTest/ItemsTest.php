<?php
declare(strict_types=1);

namespace App\Test\ApiTest;

use App\Test\Fixture\TestAccount;
use App\Test\TestSuite\AuthIntegrationTestCase;

class ItemsTest extends AuthIntegrationTestCase
{
    public function testAuthorizationGet(): void
    {
        $this->withoutAuth();
        $this->assertGet('/items', 401);
        $this->assertGet('/items/1', 401);
        $this->assertGet('/items/99', 401);

        $this->withAuthPlayer();
        $this->assertGet('/items');
        $this->assertGet('/items/1');
        $this->assertGet('/items/2', 403);
        $this->assertGet('/items/99', 404);

        $this->withAuthReadOnly();
        $this->assertGet('/items');
        $this->assertGet('/items/1');
        $this->assertGet('/items/2');
        $this->assertGet('/items/99', 404);

        $this->withAuthReferee();
        $this->assertGet('/items');
        $this->assertGet('/items/1');
        $this->assertGet('/items/2');
        $this->assertGet('/items/99', 404);

        $this->withAuthInfobalie();
        $this->assertGet('/items');
        $this->assertGet('/items/1');
        $this->assertGet('/items/2');
        $this->assertGet('/items/99', 404);
    }

    public function testAuthorizationPut(): void
    {
        $this->withoutAuth();
        $this->assertPut('/items', [], 401);
        $this->assertPut('/items/1', [], 401);
        $this->assertPut('/items/99', [], 401);

        $this->withAuthPlayer();
        $this->assertPut('/items', [], 403);
        $this->assertPut('/items/1', [], 403);
        $this->assertPut('/items/99', [], 403);

        $this->withAuthReadOnly();
        $this->assertPut('/items', [], 403);
        $this->assertPut('/items/1', [], 403);
        $this->assertPut('/items/99', [], 403);

        $this->withAuthReferee();
        $this->assertPut('/items', [], 422);
        $this->assertPut('/items/1', []);
        $this->assertPut('/items/99', [], 404);

        $this->withAuthInfobalie();
        $this->assertPut('/items', [], 422);
        $this->assertPut('/items/1', []);
        $this->assertPut('/items/99', [], 404);
    }

    public function testAuthorizationDelete(): void
    {
        $this->withoutAuth();
        $this->assertDelete('/items/1', 401);
        $this->assertDelete('/items/2', 401);
        $this->assertDelete('/items/99', 401);

        $this->withAuthPlayer();
        $this->assertDelete('/items/1', 403);
        $this->assertDelete('/items/2', 403);
        $this->assertDelete('/items/99', 403);

        $this->withAuthReadOnly();
        $this->assertDelete('/items/1', 403);
        $this->assertDelete('/items/2', 403);
        $this->assertDelete('/items/99', 403);

        $this->withAuthReferee();
        $this->assertDelete('/items/1', 403);
        $this->assertDelete('/items/2', 403);
        $this->assertDelete('/items/99', 403);

        $this->withAuthInfobalie();
        $this->assertDelete('/items/1', 403);
        $this->assertDelete('/items/2', 403);
        $this->assertDelete('/items/99', 403);
    }

    public function testCharacterItems(): void
    {
        $this->withoutAuth();
        $this->assertGet('/characters/1/1/items', 401);

        $this->withAuthPlayer();
        $this->assertGet('/characters/1/1/items');
        $this->assertGet('/characters/2/1/items', 403);

        $this->withAuthReadOnly();
        $this->assertGet('/characters/1/1/items');
        $this->assertGet('/characters/2/1/items');
    }

    public function testAddMinimal(): void
    {
        $input = [
# only required fields:
            'name' => 'item name',
            'description' => 'item description',
            'player_text' => 'player explanation',
        ];

        $expected = [
            'class' => 'Item',
            'url' => '/items/3',
            'itin' => 3,
            'name' => $input['name'],
            'description' => $input['description'],
            'player_text' => $input['player_text'],
            'referee_notes' => null,
            'notes' => null,
            'imbue_capacity' => null,
            'expiry' => null,
            'deprecated' => false,
            'plin' => null,
            'chin' => null,
            'modifier_id' => TestAccount::Referee->value,
            'creator_id' => TestAccount::Referee->value,
        ];

        $this->withAuthReferee();
        $actual = $this->assertPut('/items', $input, 201);

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
            'name' => 'item name',
            'description' => 'item description',
            'player_text' => 'player explanation',
# optional fields:
            'referee_notes' => 'hidden referee details',
            'notes' => 'infobalie notes',
            'imbue_capacity' => 10,
            'manatype_id' => 1,
            'mana_amount' => 5,
            'expiry' => '2026-06-01',
            'deprecated' => true,
            'plin' => '1',
            'chin' => '1',
# ignored fields:
            'id' => '66',
            'modifier_id' => '9',
            'creator_id' => '9',
            'ignored' => 'ignored',
        ];

        $expected = [
            'class' => 'Item',
            'url' => '/items/3',
            'itin' => 3,
            'name' => $input['name'],
            'description' => $input['description'],
            'player_text' => $input['player_text'],
            'referee_notes' => $input['referee_notes'],
            'notes' => $input['notes'],
            'imbue_capacity' => $input['imbue_capacity'],
            'manatype_id' => $input['manatype_id'],
            'mana_amount' => $input['mana_amount'],
            'expiry' => $input['expiry'],
            'deprecated' => $input['deprecated'],
            'plin' => $input['plin'],
            'chin' => $input['chin'],
            'modifier_id' => TestAccount::Referee->value,
            'creator_id' => TestAccount::Referee->value,
        ];

        $this->withAuthReferee();
        $actual = $this->assertPut('/items', $input, 201);

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
        $response = $this->assertPut('/items', [], 422);

        $errors = $this->assertErrorsResponse('/items', $response);

        # expected fields with validation errors:
        $this->assertCount(1, $errors);
        $this->assertArrayHasKey('name', $errors);
    }
}
