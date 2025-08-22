<?php
declare(strict_types=1);

namespace App\Test\ApiTest;

use App\Test\TestSuite\AuthIntegrationTestCase;

class ManatypesTest extends AuthIntegrationTestCase
{
    public function testAuthorizationGet(): void
    {
        $this->withoutAuth();
        $this->assertGet('/manatypes', 401);
        $this->assertGet('/manatypes/1', 401);
        $this->assertGet('/manatypes/99', 401);

        $this->withAuthPlayer();
        $this->assertGet('/manatypes');
        $this->assertGet('/manatypes/1');
        $this->assertGet('/manatypes/99', 404);

        $this->withAuthReadOnly();
        $this->assertGet('/manatypes');
        $this->assertGet('/manatypes/1');
        $this->assertGet('/manatypes/99', 404);

        $this->withAuthReferee();
        $this->assertGet('/manatypes');
        $this->assertGet('/manatypes/1');
        $this->assertGet('/manatypes/99', 404);

        $this->withAuthInfobalie();
        $this->assertGet('/manatypes');
        $this->assertGet('/manatypes/1');
        $this->assertGet('/manatypes/99', 404);
    }

    public function testAuthorizationPut(): void
    {
        $this->withoutAuth();
        $this->assertPut('/manatypes', [], 401);
        $this->assertPut('/manatypes/1', [], 401);

        $this->withAuthPlayer();
        $this->assertPut('/manatypes', [], 403);
        $this->assertPut('/manatypes/1', [], 403);

        $this->withAuthReadOnly();
        $this->assertPut('/manatypes', [], 403);
        $this->assertPut('/manatypes/1', [], 403);

        $this->withAuthReferee();
        $this->assertPut('/manatypes', [], 403);
        $this->assertPut('/manatypes/1', [], 403);

        $this->withAuthInfobalie();
        $this->assertPut('/manatypes', [], 403);
        $this->assertPut('/manatypes/1', [], 403);
    }

    public function testAuthorizationDelete(): void
    {
        $this->withoutAuth();
        $this->assertDelete('/manatypes/1', 401);

        $this->withAuthPlayer();
        $this->assertDelete('/manatypes/1', 403);

        $this->withAuthReadOnly();
        $this->assertDelete('/manatypes/1', 403);

        $this->withAuthReferee();
        $this->assertDelete('/manatypes/1', 403);

        $this->withAuthInfobalie();
        $this->assertDelete('/manatypes/1', 403);
    }
}
