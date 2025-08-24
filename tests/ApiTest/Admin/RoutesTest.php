<?php
declare(strict_types=1);

namespace App\Test\ApiTest\Admin;

use App\Test\TestSuite\AuthIntegrationTestCase;

class RoutesTest extends AuthIntegrationTestCase
{
    public function testAuthorization(): void
    {
        $url = '/admin/routes';

        $this->withoutAuth();
        $this->assertGet($url, 302);
        $this->assertRedirect('/admin?redirect=' . urlencode($url));

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
