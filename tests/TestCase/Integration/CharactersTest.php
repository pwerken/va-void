<?php
declare(strict_types=1);

namespace App\Test\TestCase\Integration;

use App\Test\TestSuite\AuthIntegrationTestCase;

class CharactersTest extends AuthIntegrationTestCase
{
    public function testAuthorization()
    {
        $this->withoutAuth();
        $this->assertGet('/characters', 401);
        $this->assertGet('/characters/1', 401);
        $this->assertGet('/characters/1/1', 401);
        $this->assertGet('/characters/1/1/items', 401);
        $this->assertGet('/characters/1/1/conditions', 401);
        $this->assertGet('/characters/1/1/conditions/1', 401);
        $this->assertGet('/characters/1/1/conditions/2', 401);
        $this->assertGet('/characters/1/1/powers', 401);
        $this->assertGet('/characters/1/1/powers/1', 401);
        $this->assertGet('/characters/1/1/powers/2', 401);
        $this->assertGet('/characters/1/1/skills', 401);
        $this->assertGet('/characters/1/1/skills/1', 401);
        $this->assertGet('/characters/1/1/students', 401);
        $this->assertGet('/characters/1/1/teacher', 401);
        $this->assertGet('/characters/1/2', 404);
        $this->assertGet('/characters/1/2/items', 404);
        $this->assertGet('/characters/1/2/conditions', 404);
        $this->assertGet('/characters/1/2/conditions/1', 404);
        $this->assertGet('/characters/1/2/conditions/2', 404);
        $this->assertGet('/characters/1/2/powers', 404);
        $this->assertGet('/characters/1/2/powers/1', 404);
        $this->assertGet('/characters/1/2/powers/2', 404);
        $this->assertGet('/characters/1/2/skills', 404);
        $this->assertGet('/characters/1/2/skills/1', 404);
        $this->assertGet('/characters/1/2/students', 404);
        $this->assertGet('/characters/1/2/teacher', 404);
        $this->assertGet('/characters/2', 401);
        $this->assertGet('/characters/2/1', 401);
        $this->assertGet('/characters/2/1/items', 401);
        $this->assertGet('/characters/2/1/conditions', 401);
        $this->assertGet('/characters/2/1/conditions/1', 401);
        $this->assertGet('/characters/2/1/conditions/2', 401);
        $this->assertGet('/characters/2/1/powers', 401);
        $this->assertGet('/characters/2/1/powers/1', 401);
        $this->assertGet('/characters/2/1/powers/2', 401);
        $this->assertGet('/characters/2/1/skills', 401);
        $this->assertGet('/characters/2/1/skills/1', 401);
        $this->assertGet('/characters/2/1/skills/2', 401);
        $this->assertGet('/characters/2/1/students', 401);
        $this->assertGet('/characters/2/1/teacher', 401);
        $this->assertGet('/characters/2/2', 404);
        $this->assertGet('/characters/2/2/items', 404);
        $this->assertGet('/characters/2/2/conditions', 404);
        $this->assertGet('/characters/2/2/conditions/1', 404);
        $this->assertGet('/characters/2/2/conditions/2', 404);
        $this->assertGet('/characters/2/2/powers', 404);
        $this->assertGet('/characters/2/2/powers/1', 404);
        $this->assertGet('/characters/2/2/powers/2', 404);
        $this->assertGet('/characters/2/2/skills', 404);
        $this->assertGet('/characters/2/2/skills/1', 404);
        $this->assertGet('/characters/2/2/skills/2', 404);
        $this->assertGet('/characters/2/2/students', 404);
        $this->assertGet('/characters/2/2/teacher', 404);
        $this->assertGet('/characters/99', 401);
        $this->assertGet('/characters/99/1', 404);
        $this->assertGet('/characters/99/1/items', 404);
        $this->assertGet('/characters/99/1/conditions', 404);
        $this->assertGet('/characters/99/1/conditions/1', 404);
        $this->assertGet('/characters/99/1/powers', 404);
        $this->assertGet('/characters/99/1/powers/1', 404);
        $this->assertGet('/characters/99/1/skills', 404);
        $this->assertGet('/characters/99/1/skills/1', 404);
        $this->assertGet('/characters/99/1/students', 404);
        $this->assertGet('/characters/99/1/teacher', 404);

        $this->withAuthPlayer();
        $this->assertGet('/characters');
        $this->assertGet('/characters/1');
        $this->assertGet('/characters/1/1');
        $this->assertGet('/characters/1/1/items');
        $this->assertGet('/characters/1/1/conditions');
        $this->assertGet('/characters/1/1/conditions/1');
        $this->assertGet('/characters/1/1/conditions/2', 404);
        $this->assertGet('/characters/1/1/powers');
        $this->assertGet('/characters/1/1/powers/1');
        $this->assertGet('/characters/1/1/powers/2', 404);
        $this->assertGet('/characters/1/1/skills');
        $this->assertGet('/characters/1/1/skills/1');
        $this->assertGet('/characters/1/1/skills/2', 404);
#       $this->assertGet('/characters/1/1/students');
#       $this->assertGet('/characters/1/1/teacher'); // FIXME fixture
        $this->assertGet('/characters/1/2', 404);
        $this->assertGet('/characters/1/2/items', 404);
        $this->assertGet('/characters/1/2/conditions', 404);
        $this->assertGet('/characters/1/2/conditions/1', 404);
        $this->assertGet('/characters/1/2/conditions/2', 404);
        $this->assertGet('/characters/1/2/powers', 404);
        $this->assertGet('/characters/1/2/powers/1', 404);
        $this->assertGet('/characters/1/2/powers/2', 404);
        $this->assertGet('/characters/1/2/skills', 404);
        $this->assertGet('/characters/1/2/skills/1', 404);
        $this->assertGet('/characters/1/2/skills/2', 404);
        $this->assertGet('/characters/1/2/students', 404);
        $this->assertGet('/characters/1/2/teacher', 404);
        $this->assertGet('/characters/2', 403);
        $this->assertGet('/characters/2/1', 403);
        $this->assertGet('/characters/2/1/items', 403);
        $this->assertGet('/characters/2/1/conditions', 403);
        $this->assertGet('/characters/2/1/conditions/1', 403);
        $this->assertGet('/characters/2/1/conditions/2', 403);
        $this->assertGet('/characters/2/1/powers', 403);
        $this->assertGet('/characters/2/1/powers/1', 403);
        $this->assertGet('/characters/2/1/powers/2', 403);
        $this->assertGet('/characters/2/1/skills', 403);
        $this->assertGet('/characters/2/1/skills/1', 403);
        $this->assertGet('/characters/2/1/skills/2', 403);
#       $this->assertGet('/characters/2/1/students', 403);
#       $this->assertGet('/characters/2/1/teacher', 403);
        $this->assertGet('/characters/99', 404);
        $this->assertGet('/characters/99/1', 404);
        $this->assertGet('/characters/99/1/items', 404);
        $this->assertGet('/characters/99/1/conditions', 404);
        $this->assertGet('/characters/99/1/conditions/1', 404);
        $this->assertGet('/characters/99/1/powers', 404);
        $this->assertGet('/characters/99/1/powers/1', 404);
        $this->assertGet('/characters/99/1/skills', 404);
        $this->assertGet('/characters/99/1/skills/1', 404);
#       $this->assertGet('/characters/99/1/students', 404);
#       $this->assertGet('/characters/99/1/teacher', 404);

        $this->withAuthReadOnly();
        $this->assertGet('/characters');
        $this->assertGet('/characters/1');
        $this->assertGet('/characters/1/1');
        $this->assertGet('/characters/1/1/items');
        $this->assertGet('/characters/1/1/conditions');
        $this->assertGet('/characters/1/1/conditions/1');
        $this->assertGet('/characters/1/1/conditions/2', 404);
        $this->assertGet('/characters/1/1/powers');
        $this->assertGet('/characters/1/1/powers/1');
        $this->assertGet('/characters/1/1/powers/2', 404);
        $this->assertGet('/characters/1/1/skills');
        $this->assertGet('/characters/1/1/skills/1');
        $this->assertGet('/characters/1/1/skills/2', 404);
#       $this->assertGet('/characters/1/1/students');
#       $this->assertGet('/characters/1/1/teacher'); // FIXME fixture
        $this->assertGet('/characters/1/2', 404);
        $this->assertGet('/characters/1/2/items', 404);
        $this->assertGet('/characters/1/2/conditions', 404);
        $this->assertGet('/characters/1/2/conditions/1', 404);
        $this->assertGet('/characters/1/2/conditions/2', 404);
        $this->assertGet('/characters/1/2/powers', 404);
        $this->assertGet('/characters/1/2/powers/1', 404);
        $this->assertGet('/characters/1/2/powers/2', 404);
        $this->assertGet('/characters/1/2/skills', 404);
        $this->assertGet('/characters/1/2/skills/1', 404);
        $this->assertGet('/characters/1/2/skills/2', 404);
        $this->assertGet('/characters/1/2/students', 404);
        $this->assertGet('/characters/1/2/teacher', 404);
        $this->assertGet('/characters/2');
        $this->assertGet('/characters/2/1');
        $this->assertGet('/characters/2/1/items');
        $this->assertGet('/characters/2/1/conditions');
        $this->assertGet('/characters/2/1/conditions/1');
        $this->assertGet('/characters/2/1/conditions/2');
        $this->assertGet('/characters/2/1/powers');
        $this->assertGet('/characters/2/1/powers/1');
        $this->assertGet('/characters/2/1/powers/2');
        $this->assertGet('/characters/2/1/skills');
        $this->assertGet('/characters/2/1/skills/1', 404);
        $this->assertGet('/characters/2/1/skills/2', 404);
#       $this->assertGet('/characters/2/1/students', 403);
#       $this->assertGet('/characters/2/1/teacher', 403);
        $this->assertGet('/characters/99', 404);
        $this->assertGet('/characters/99/1', 404);
        $this->assertGet('/characters/99/1/items', 404);
        $this->assertGet('/characters/99/1/conditions', 404);
        $this->assertGet('/characters/99/1/conditions/1', 404);
        $this->assertGet('/characters/99/1/powers', 404);
        $this->assertGet('/characters/99/1/powers/1', 404);
        $this->assertGet('/characters/99/1/skills', 404);
        $this->assertGet('/characters/99/1/skills/1', 404);
#       $this->assertGet('/characters/99/1/students', 404);
#       $this->assertGet('/characters/99/1/teacher', 404);
    }

    public function testAddCharacterMinimal()
    {
        $input = [
# required fields:
            'name' => 'new character',
        ];

        $expected = [
            'class' => 'Character',
            'url' => '/characters/2/2',
            'plin' => 2,
            'chin' => 2,
            'name' => $input['name'],
            'xp' => 15.0,
            'belief' => '-',
            'group' => '-',
            'world' => '-',
            'soulpath' => '',
            'faction' => '-',
            'status' => 'inactive',
            'referee_notes' => null,
            'notes' => null,
            'modifier_id' => self::$PLIN_REFEREE,
        ];

        $this->withAuthReferee();
        $actual = $this->assertPut('/players/2/characters', $input, 201);

        foreach ($expected as $key => $value) {
            $this->assertArrayKeyValue($key, $value, $actual);
        }
        $this->assertDateTimeNow($actual['modified']);
    }

    public function testAddCharacterComplete()
    {
        $input = [
# required fields:
            'name' => 'new character',
# optional fields:
            'chin' => 5,
            'xp' => '20.25',
            'faction' => 'Void',
            'soulpath' => 'SO',
            'belief' => 'Solar',
            'group' => 'hullie',
            'world' => 'home',
            'status' => 'dead',
            'referee_notes' => 'hidden referee details',
            'notes' => 'infobalie notes',
# ignored fields:
            'plin' => 9,
            'modifier_id' => 9,
            'ignored' => 'ignored',
        ];

        $expected = [
            'class' => 'Character',
            'url' => '/characters/2/5',
            'plin' => 2,
            'chin' => $input['chin'],
            'name' => $input['name'],
            'xp' => $input['xp'],
            'faction' => $input['faction'],
            'soulpath' => $input['soulpath'],
            'belief' => $input['belief'],
            'group' => $input['group'],
            'world' => $input['world'],
            'status' => $input['status'],
            'referee_notes' => $input['referee_notes'],
            'notes' => $input['notes'],
            'modifier_id' => self::$PLIN_REFEREE,
        ];

        $this->withAuthReferee();
        $actual = $this->assertPut('/players/2/characters', $input, 201);

        foreach ($expected as $key => $value) {
            $this->assertArrayKeyValue($key, $value, $actual);
        }
        $this->assertDateTimeNow($actual['modified']);
    }

    public function testRequiredFieldsValidation()
    {
        $url = '/players/1/characters';

        $this->withAuthReferee();
        $response = $this->assertPut($url, [], 422);

        $errors = $this->assertErrorsResponse($url, $response);

        # expected fields with validation errors:
        $this->assertCount(1, $errors);
        $this->assertArrayHasKey('name', $errors);
    }
}
