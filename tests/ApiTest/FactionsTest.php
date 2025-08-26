<?php
declare(strict_types=1);

namespace App\Test\ApiTest;

use App\Test\TestSuite\AuthIntegrationTestCase;

class FactionsTest extends AuthIntegrationTestCase
{
    public function testAuthorizationGet(): void
    {
        $this->withoutAuth();
        $this->assertGet('/factions', 401);
        $this->assertGet('/factions/1', 401);
        $this->assertGet('/factions/1/characters', 401);
        $this->assertGet('/factions/99', 401);
        $this->assertGet('/factions/99/characters', 401);

        $this->withAuthPlayer();
        $this->assertGet('/factions');
        $this->assertGet('/factions/1');
        $this->assertGet('/factions/1/characters', 403);
        $this->assertGet('/factions/99', 404);
        $this->assertGet('/factions/99/characters', 403);

        $this->withAuthReadOnly();
        $this->assertGet('/factions');
        $this->assertGet('/factions/1');
        $this->assertGet('/factions/1/characters');
        $this->assertGet('/factions/99', 404);
        $this->assertGet('/factions/99/characters', 404);

        $this->withAuthReferee();
        $this->assertGet('/factions');
        $this->assertGet('/factions/1');
        $this->assertGet('/factions/1/characters');
        $this->assertGet('/factions/99', 404);
        $this->assertGet('/factions/99/characters', 404);

        $this->withAuthInfobalie();
        $this->assertGet('/factions');
        $this->assertGet('/factions/1');
        $this->assertGet('/factions/1/characters');
        $this->assertGet('/factions/99', 404);
        $this->assertGet('/factions/99/characters', 404);
    }

    public function testAuthorizationPut(): void
    {
        $this->withoutAuth();
        $this->assertPut('/factions', [], 401);
        $this->assertPut('/factions/1', [], 401);

        $this->withAuthPlayer();
        $this->assertPut('/factions', [], 403);
        $this->assertPut('/factions/1', [], 403);

        $this->withAuthReadOnly();
        $this->assertPut('/factions', [], 403);
        $this->assertPut('/factions/1', [], 403);

        $this->withAuthReferee();
        $this->assertPut('/factions', [], 403);
        $this->assertPut('/factions/1', [], 403);

        $this->withAuthInfobalie();
        $this->assertPut('/factions', [], 403);
        $this->assertPut('/factions/1', [], 403);
    }

    public function testAuthorizationDelete(): void
    {
        $this->withoutAuth();
        $this->assertDelete('/factions/1', 401);

        $this->withAuthPlayer();
        $this->assertDelete('/factions/1', 403);

        $this->withAuthReadOnly();
        $this->assertDelete('/factions/1', 403);

        $this->withAuthReferee();
        $this->assertDelete('/factions/1', 403);

        $this->withAuthInfobalie();
        $this->assertDelete('/factions/1', 403);
    }

    public function testSuperPermissions(): void
    {
        $this->withAuthSuper();
        $this->assertPut('/factions', [], 422);
        $this->assertDelete('/factions/2', 422);

        $this->assertPut('/factions', ['name' => 'Test'], 201);
        $this->assertPut('/factions/3', ['name' => 'Edit']);
        $this->assertDelete('/factions/3');
    }
}
