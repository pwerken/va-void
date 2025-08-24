<?php
declare(strict_types=1);

namespace App\Test\ApiTest\Admin;

use App\Test\TestSuite\AuthIntegrationTestCase;

class StatsTest extends AuthIntegrationTestCase
{
    public function testAuthorization(): void
    {
        $url = '/admin/stats';

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

    public function testOptions(): void
    {
        $this->withAuthReadOnly();
        $this->assertGet('/admin/stats?since=2018-01-01&selected=xp-curve');
        $this->assertGet('/admin/stats?since=2018-01-01&selected=items-per-char');
        $this->assertGet('/admin/stats?since=2018-01-01&selected=powers-conditions-per-char');
        $this->assertGet('/admin/stats?since=2018-01-01&selected=attunement-powers-per-char');
        $this->assertGet('/admin/stats?since=2018-01-01&selected=attunement-per-char');
    }
}
