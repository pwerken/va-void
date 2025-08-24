<?php
declare(strict_types=1);

namespace App\Test\ApiTest\Admin;

use App\Test\TestSuite\AuthIntegrationTestCase;

class SkillsTest extends AuthIntegrationTestCase
{
    public function testAuthorization(): void
    {
        $url = '/admin/skills';

        $this->withoutAuth();
        $this->assertGet($url, 302);
        $this->assertRedirect('/admin?redirect=' . urlencode($url));

        $this->withAuthPlayer();
        $this->assertGet($url, 403);

        $this->withAuthReadOnly();
        $this->assertGet($url);

        $this->withAuthReferee();
        $this->assertGet($url);

        $this->withAuthInfobalie();
        $this->assertGet($url);
    }

    public function testSelect(): void
    {
        $this->withAuthReadOnly();
        $this->assertGet('/admin/skills?skills=&skills[]=1&skills[]=2&and=1&since=2018-01-01');
    }
}
