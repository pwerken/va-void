<?php
declare(strict_types=1);

namespace App\Test\ApiTest\Admin;

use App\Test\TestSuite\AuthIntegrationTestCase;

class ChecksTest extends AuthIntegrationTestCase
{
    public function testAuthorization(): void
    {
        $url = '/admin/checks';

        $this->withoutAuth();
        $this->assertGet($url);

        $this->withAuthPlayer();
        $this->assertGet($url);

        $this->withAuthReadOnly();
        $this->assertGet($url);

        $this->withAuthReferee();
        $this->assertGet($url);

        $this->withAuthInfobalie();
        $this->assertGet($url);
    }
}
