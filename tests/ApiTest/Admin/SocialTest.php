<?php
declare(strict_types=1);

namespace App\Test\ApiTest\Admin;

use App\Test\TestSuite\AuthIntegrationTestCase;

class SocialTest extends AuthIntegrationTestCase
{
    protected string $url = '/admin/social';

    public function testNotLoggedIn(): void
    {
        $this->assertGet($this->url, 302);
        $this->assertRedirect('/admin?redirect=' . urlencode($this->url));

        $this->assertPost($this->url, [], 302);
        $this->assertRedirect('/admin');

        $this->assertGet($this->url . '/all', 302);
        $this->assertRedirect('/admin?redirect=' . urlencode($this->url . '/all'));

        $this->assertPost($this->url . '/all', [], 302);
        $this->assertRedirect('/admin');

        $this->assertGet($this->url . '/callback', 302);
        $this->assertRedirect('/admin');
    }

    public function testAsPlayer(): void
    {
        $this->withAuthPlayer();

        $this->assertGet($this->url, 403);
        $this->assertPost($this->url, [], 403);

        $this->assertGet($this->url . '/all', 403);
        $this->assertPost($this->url . '/all', [], 403);

        $this->assertGet($this->url . '/callback', 302);
        $this->assertRedirect('/admin');
    }

    public function testAsReadOnly(): void
    {
        $this->withAuthReadOnly();

        $this->assertGet($this->url, 403);
        $this->assertPost($this->url, [], 403);

        $this->assertGet($this->url . '/all', 403);
        $this->assertPost($this->url . '/all', [], 403);

        $this->assertGet($this->url . '/callback', 302);
        $this->assertRedirect('/admin');
    }

    public function testAsReferee(): void
    {
        $this->withAuthReferee();

        $this->assertGet($this->url, 403);
        $this->assertPost($this->url, [], 403);

        $this->assertGet($this->url . '/all', 403);
        $this->assertPost($this->url . '/all', [], 403);

        $this->assertGet($this->url . '/callback', 302);
        $this->assertRedirect('/admin');
    }

    public function testAsInfobalie(): void
    {
        $this->withAuthInfobalie();

        $this->assertGet($this->url);
        $this->assertPost($this->url, [], 302);
        $this->assertRedirect($this->url);

        $this->assertGet($this->url . '/all');
        $this->assertPost($this->url . '/all', [], 302);
        $this->assertRedirect($this->url . '/all');

        $this->assertGet($this->url . '/callback', 302);
        $this->assertRedirect('/admin');
    }
}
