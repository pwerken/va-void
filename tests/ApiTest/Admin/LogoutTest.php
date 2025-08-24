<?php
declare(strict_types=1);

namespace App\Test\ApiTest\Admin;

use App\Test\TestSuite\AuthIntegrationTestCase;

class LogoutTest extends AuthIntegrationTestCase
{
    public function testAuthorization(): void
    {
        $url = '/admin/logout';

        $this->withoutAuth();
        $this->assertGet($url, 302);
        $this->assertRedirect('/admin');

        $this->withAuthPlayer();
        $this->assertGet($url, 302);
        $this->assertRedirect('/admin');

        $this->withAuthReadOnly();
        $this->assertGet($url, 302);
        $this->assertRedirect('/admin');

        $this->withAuthReferee();
        $this->assertGet($url, 302);
        $this->assertRedirect('/admin');

        $this->withAuthInfobalie();
        $this->assertGet($url, 302);
        $this->assertRedirect('/admin');
    }
}
