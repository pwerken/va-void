<?php
declare(strict_types=1);

namespace App\Test\ApiTest\Admin;

use App\Test\TestSuite\AuthIntegrationTestCase;

class PrintingTest extends AuthIntegrationTestCase
{
    protected string $url = '/admin/printing';

    public function testNotLoggedIn(): void
    {
        $this->withoutAuth();

        $this->assertGet($this->url, 302);
        $this->assertRedirect('/admin?redirect=' . urlencode($this->url));

        $this->assertPost($this->url, [], 302);
        $this->assertRedirect('/admin?redirect=' . urlencode($this->url));

        $this->assertGet($this->url . '/single', 302);
        $this->assertRedirect('/admin?redirect=' . urlencode($this->url . '/single'));

        $this->assertGet($this->url . '/double', 302);
        $this->assertRedirect('/admin?redirect=' . urlencode($this->url . '/double'));
    }

    public function testAsPlayer(): void
    {
        $this->withAuthPlayer();

        $this->assertGet($this->url, 403);

        $this->assertPost($this->url, [], 403);

        $this->assertGet($this->url . '/single', 403);
        $this->assertGet($this->url . '/double', 403);
    }

    public function testAsReadOnly(): void
    {
        $this->withAuthReadOnly();

        $this->assertGet($this->url);

        $this->assertPost($this->url, [], 302);
        $this->assertRedirect($this->url);

        $this->assertGet($this->url . '/single', 403);
        $this->assertGet($this->url . '/double', 403);
    }

    public function testAsReferee(): void
    {
        $this->withAuthReferee();

        $this->assertGet($this->url);

        $this->assertPost($this->url, [], 302);
        $this->assertRedirect($this->url);

        $this->assertGet($this->url . '/single', 403);
        $this->assertGet($this->url . '/double', 403);
    }

    public function testAsInfobalie(): void
    {
        $this->withAuthInfobalie();

        $this->assertGet($this->url);

        $this->assertPost($this->url, [], 302);
        $this->assertRedirect($this->url);

        $this->assertGet($this->url . '/single');
        $this->assertGet($this->url . '/double');
    }
}
