<?php
declare(strict_types=1);

namespace App\Test\ApiTest;

use App\Test\TestSuite\AuthIntegrationTestCase;

class SocialProfilesTest extends AuthIntegrationTestCase
{
    public function testAuthorizationGet(): void
    {
        $this->withoutAuth();
        $this->assertGet('/players/1/socials', 401);
        $this->assertGet('/players/1/socials/1', 401);
        $this->assertGet('/players/2/socials', 401);
        $this->assertGet('/players/99/socials', 401);

        $this->withAuthPlayer();
        $this->assertGet('/players/1/socials');
        $this->assertGet('/players/1/socials/1');
        $this->assertGet('/players/2/socials', 403);
        $this->assertGet('/players/99/socials', 403);

        $this->withAuthReadOnly();
        $this->assertGet('/players/1/socials', 403);
        $this->assertGet('/players/1/socials/1', 403);
        $this->assertGet('/players/2/socials');
        $this->assertGet('/players/99/socials', 403);

        $this->withAuthReferee();
        $this->assertGet('/players/1/socials', 403);
        $this->assertGet('/players/1/socials/1', 403);
        $this->assertGet('/players/2/socials', 403);
        $this->assertGet('/players/99/socials', 403);

        $this->withAuthInfobalie();
        $this->assertGet('/players/1/socials');
        $this->assertGet('/players/1/socials/1');
        $this->assertGet('/players/2/socials');
        $this->assertGet('/players/99/socials', 404);
    }

    public function testAuthorizationPut(): void
    {
        $this->withoutAuth();
        $this->assertPut('/players/1/socials', [], 401);
        $this->assertPut('/players/1/socials/1', [], 401);
        $this->assertPut('/players/2/socials', [], 401);
        $this->assertPut('/players/99/socials', [], 401);

        $this->withAuthPlayer();
        $this->assertPut('/players/1/socials', [], 403);
        $this->assertPut('/players/1/socials/1', [], 403);
        $this->assertPut('/players/2/socials', [], 403);
        $this->assertPut('/players/99/socials', [], 403);

        $this->withAuthReadOnly();
        $this->assertPut('/players/1/socials', [], 403);
        $this->assertPut('/players/1/socials/1', [], 403);
        $this->assertPut('/players/2/socials', [], 403);
        $this->assertPut('/players/99/socials', [], 403);

        $this->withAuthReferee();
        $this->assertPut('/players/1/socials', [], 403);
        $this->assertPut('/players/1/socials/1', [], 403);
        $this->assertPut('/players/2/socials', [], 403);
        $this->assertPut('/players/99/socials', [], 403);

        $this->withAuthInfobalie();
        $this->assertPut('/players/1/socials', [], 403);
        $this->assertPut('/players/1/socials/1', [], 403);
        $this->assertPut('/players/2/socials', [], 403);
        $this->assertPut('/players/99/socials', [], 403);
    }

    public function testAuthorizationDelete(): void
    {
        $this->withoutAuth();
        $this->assertDelete('/players/1/socials/1', 401);

        $this->withAuthReadOnly();
        $this->assertDelete('/players/1/socials/1', 403);

        $this->withAuthReferee();
        $this->assertDelete('/players/1/socials/1', 403);

        $this->withAuthInfobalie();
        $this->assertDelete('/players/1/socials/1', 204);
    }

    public function testDeleteOwn(): void
    {
        $this->withAuthPlayer();
        $this->assertDelete('/players/1/socials/1', 204);
    }
}
