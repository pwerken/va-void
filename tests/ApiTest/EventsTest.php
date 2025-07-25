<?php
declare(strict_types=1);

namespace App\Test\ApiTest;

use App\Test\TestSuite\AuthIntegrationTestCase;

class EventsTest extends AuthIntegrationTestCase
{
    public function testAuthorization(): void
    {
        $this->withoutAuth();
        $this->assertGet('/events', 401);
        $this->assertGet('/events/1', 401);
        $this->assertGet('/events/99', 401);
        $this->assertPut('/events', [], 401);
        $this->assertPut('/events/1', [], 401);
        $this->assertDelete('/events/1', 401);

        $this->withAuthPlayer();
        $this->assertGet('/events');
        $this->assertGet('/events/1');
        $this->assertGet('/events/99', 404);
        $this->assertPut('/events', [], 403);
        $this->assertPut('/events/1', [], 403);
        $this->assertDelete('/events/1', 403);

        $this->withAuthReadOnly();
        $this->assertGet('/events');
        $this->assertGet('/events/1');
        $this->assertGet('/events/99', 404);
        $this->assertPut('/events', [], 403);
        $this->assertPut('/events/1', [], 403);
        $this->assertDelete('/events/1', 403);

        $this->withAuthReferee();
        $this->assertGet('/events');
        $this->assertGet('/events/1');
        $this->assertGet('/events/99', 404);
        $this->assertPut('/events', [], 403);
        $this->assertPut('/events/1', [], 403);
        $this->assertDelete('/events/1', 403);

        $this->withAuthInfobalie();
        $this->assertGet('/events');
        $this->assertGet('/events/1');
        $this->assertGet('/events/99', 404);
        $this->assertPut('/events', [], 403);
        $this->assertPut('/events/1', [], 403);
        $this->assertDelete('/events/1', 403);
    }
}
