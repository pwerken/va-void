<?php
declare(strict_types=1);

namespace App\Test\ApiTest\Admin;

use App\Test\TestSuite\AuthIntegrationTestCase;

class MigrationsTest extends AuthIntegrationTestCase
{
    public function testAuthorization(): void
    {
        $url = '/admin/migrations';

        $this->withoutAuth();
        $this->assertGet($url, 302);
        $this->assertRedirect('/admin?redirect=' . urlencode($url));

        $this->withAuthPlayer();
        $this->assertGet($url, 403);

        $this->withAuthReadOnly();
        $this->assertGet($url, 403);

        $this->withAuthReferee();
        $this->assertGet($url, 403);

        $this->withAuthInfobalie();
        $this->assertGet($url);
    }
}
