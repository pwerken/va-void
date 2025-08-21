<?php
declare(strict_types=1);

namespace App\Test\ApiTest;

use App\Test\TestSuite\AuthIntegrationTestCase;

class EventsTest extends AuthIntegrationTestCase
{
    public function testAuthorizationGet(): void
    {
        $this->withoutAuth();
        $this->assertGet('/events', 401);
        $this->assertGet('/events/1', 401);
        $this->assertGet('/events/99', 401);

        $this->withAuthPlayer();
        $this->assertGet('/events');
        $this->assertGet('/events/1');
        $this->assertGet('/events/99', 404);

        $this->withAuthReadOnly();
        $this->assertGet('/events');
        $this->assertGet('/events/1');
        $this->assertGet('/events/99', 404);

        $this->withAuthReferee();
        $this->assertGet('/events');
        $this->assertGet('/events/1');
        $this->assertGet('/events/99', 404);

        $this->withAuthInfobalie();
        $this->assertGet('/events');
        $this->assertGet('/events/1');
        $this->assertGet('/events/99', 404);
    }

    public function testAuthorizationPut(): void
    {
        $this->withoutAuth();
        $this->assertPut('/events', [], 401);
        $this->assertPut('/events/1', [], 401);

        $this->withAuthPlayer();
        $this->assertPut('/events', [], 403);
        $this->assertPut('/events/1', [], 403);

        $this->withAuthReadOnly();
        $this->assertPut('/events', [], 403);
        $this->assertPut('/events/1', [], 403);

        $this->withAuthReferee();
        $this->assertPut('/events', [], 403);
        $this->assertPut('/events/1', [], 403);

        $this->withAuthInfobalie();
        $this->assertPut('/events', [], 403);
        $this->assertPut('/events/1', [], 403);
    }

    public function testAuthorizationDelete(): void
    {
        $this->withoutAuth();
        $this->assertDelete('/events/1', 401);

        $this->withAuthPlayer();
        $this->assertDelete('/events/1', 403);

        $this->withAuthReadOnly();
        $this->assertDelete('/events/1', 403);

        $this->withAuthReferee();
        $this->assertDelete('/events/1', 403);

        $this->withAuthInfobalie();
        $this->assertDelete('/events/1', 403);
    }

    public function testEvents(): void
    {
        $this->withAuthSuper();
        $this->assertPut('/events', [], 422);
        $this->assertPut('/events', ['name' => 'Test'], 201);
        $this->assertPut('/events/2', ['name' => 'Edit']);
        $this->assertDelete('/events/2');
    }
}
