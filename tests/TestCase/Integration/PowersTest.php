<?php
declare(strict_types=1);

namespace App\Test\TestCase\Integration;

use App\Test\TestSuite\AuthIntegrationTestCase;

class PowersTest extends AuthIntegrationTestCase
{
    public function testAuthorization()
    {
        $this->withoutAuth();
        $this->assertGet('/powers', 401);
        $this->assertGet('/powers/1', 401);
        $this->assertGet('/powers/1/characters', 401);
        $this->assertGet('/powers/2', 401);
        $this->assertGet('/powers/2/characters', 401);
        $this->assertGet('/powers/99', 401);
        $this->assertGet('/powers/99/characters', 401);
        $this->assertPut('/powers', [], 401);
        $this->assertPut('/powers/1', [], 401);
        $this->assertPut('/powers/2', [], 401);
        $this->assertPut('/powers/99', [], 401);

        $this->withAuthPlayer();
        $this->assertGet('/powers');
        $this->assertGet('/powers/1');
        $this->assertGet('/powers/1/characters', 403);
        $this->assertGet('/powers/2', 403);
        $this->assertGet('/powers/2/characters', 403);
        $this->assertGet('/powers/99', 404);
        $this->assertGet('/powers/99/characters', 403);
        $this->assertPut('/powers', [], 403);
        $this->assertPut('/powers/1', [], 403);
        $this->assertPut('/powers/2', [], 403);
        $this->assertPut('/powers/99', [], 403);

        $this->withAuthReadOnly();
        $this->assertGet('/powers');
        $this->assertGet('/powers/1');
        $this->assertGet('/powers/1/characters');
        $this->assertGet('/powers/2');
        $this->assertGet('/powers/2/characters');
        $this->assertGet('/powers/99', 404);
        $this->assertGet('/powers/99/characters', 404);
        $this->assertPut('/powers', [], 403);
        $this->assertPut('/powers/1', [], 403);
        $this->assertPut('/powers/2', [], 403);
        $this->assertPut('/powers/99', [], 403);
    }

    public function testAddPower()
    {
        $this->withAuthReferee();

        $input = [
# required fields:
            'name' => 'power name',
            'player_text' => 'player explenation',
# optional fields:
            'referee_notes' => 'hidden referee details',
            'notes' => 'infobalie notes',
            'deprecated' => false,
# ignored fields:
            'poin' => 66,
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
            'modifier_id' => self::$PLIN_REFEREE,
            'creator_id' => self::$PLIN_REFEREE,
        ];

        $actual = $this->assertPut('/powers', $input, 201);

        foreach ($expected as $key => $value) {
            $this->assertArrayKeyValue($key, $value, $actual);
        }
        $this->assertDateTimeNow($actual['modified']);
        $this->assertDateTimeNow($actual['created']);

        $this->assertArrayNotHasKey('ignored', $actual);
    }

    public function testRequiredFieldsValidation()
    {
        $this->withAuthReferee();

        $response = $this->assertPut('/powers', [], 422);
        $errors = $this->assertErrorsResponse('/powers', $response);

        # expected fields with validation errors:
        $this->assertCount(2, $errors);
        $this->assertArrayHasKey('name', $errors);
        $this->assertArrayHasKey('player_text', $errors);
    }
}
