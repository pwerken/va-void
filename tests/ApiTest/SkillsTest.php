<?php
declare(strict_types=1);

namespace App\Test\ApiTest;

use App\Test\TestSuite\AuthIntegrationTestCase;

class SkillsTest extends AuthIntegrationTestCase
{
    public function testAuthorizationGet(): void
    {
        $this->withoutAuth();
        $this->assertGet('/skills', 401);
        $this->assertGet('/skills/1', 401);
        $this->assertGet('/skills/1/characters', 401);
        $this->assertGet('/skills/99', 401);
        $this->assertGet('/skills/99/characters', 401);

        $this->withAuthPlayer();
        $this->assertGet('/skills');
        $this->assertGet('/skills/1');
        $this->assertGet('/skills/1/characters', 403);
        $this->assertGet('/skills/99', 404);
        $this->assertGet('/skills/99/characters', 403);

        $this->withAuthReadOnly();
        $this->assertGet('/skills');
        $this->assertGet('/skills/1');
        $this->assertGet('/skills/1/characters');
        $this->assertGet('/skills/99', 404);
        $this->assertGet('/skills/99/characters', 404);

        $this->withAuthReferee();
        $this->assertGet('/skills');
        $this->assertGet('/skills/1');
        $this->assertGet('/skills/1/characters');
        $this->assertGet('/skills/99', 404);
        $this->assertGet('/skills/99/characters', 404);

        $this->withAuthInfobalie();
        $this->assertGet('/skills');
        $this->assertGet('/skills/1');
        $this->assertGet('/skills/1/characters');
        $this->assertGet('/skills/99', 404);
        $this->assertGet('/skills/99/characters', 404);
    }

    public function testAuthorizationPut(): void
    {
        $this->withoutAuth();
        $this->assertPut('/skills', [], 401);
        $this->assertPut('/skills/1', [], 401);

        $this->withAuthPlayer();
        $this->assertPut('/skills', [], 403);
        $this->assertPut('/skills/1', [], 403);

        $this->withAuthReadOnly();
        $this->assertPut('/skills', [], 403);
        $this->assertPut('/skills/1', [], 403);

        $this->withAuthReferee();
        $this->assertPut('/skills', [], 403);
        $this->assertPut('/skills/1', [], 403);

        $this->withAuthInfobalie();
        $this->assertPut('/skills', [], 403);
        $this->assertPut('/skills/1', [], 403);
    }

    public function testAuthorizationDelete(): void
    {
        $this->withoutAuth();
        $this->assertDelete('/skills/1', 401);

        $this->withAuthPlayer();
        $this->assertDelete('/skills/1', 403);

        $this->withAuthReadOnly();
        $this->assertDelete('/skills/1', 403);

        $this->withAuthReferee();
        $this->assertDelete('/skills/1', 403);

        $this->withAuthInfobalie();
        $this->assertDelete('/skills/1', 403);
    }

    public function testSuperPermissions(): void
    {
        $this->withAuthSuper();
        $this->assertPut('/skills', [], 422);
        $this->assertDelete('/skills/2', 422);

        $input = [
            'name' => 'testing',
            'cost' => 99,
        ];
        $expected = [
            'class' => 'Skill',
            'url' => '/skills/4',
            'name' => $input['name'],
            'cost' => $input['cost'],
            'base_max' => 1,
            'times_max' => 1,
            'mana_amount' => null,
            'manatype' => null,
            'blanks' => false,
            'loresheet' => false,
            'sort_order' => null,
            'deprecated' => false,
        ];
        $actual = $this->assertPut('/skills', $input, 201);
        foreach ($expected as $key => $value) {
            $this->assertArrayKeyValue($key, $value, $actual);
        }

        $input = [
            'name' => 'editing',
        ];
        $expected['name'] = $input['name'];
        $actual = $this->assertPut('/skills/4', $input);
        foreach ($expected as $key => $value) {
            $this->assertArrayKeyValue($key, $value, $actual);
        }

        $this->assertDelete('/skills/4');
    }
}
