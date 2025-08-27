<?php
declare(strict_types=1);

namespace App\Test\ApiTest\Admin;

use App\Test\TestSuite\AuthIntegrationTestCase;

class AuthorizationTest extends AuthIntegrationTestCase
{
    protected string $url = '/admin/authorization';

    public function testNotLoggedIn(): void
    {
        $this->withoutAuth();

        $this->assertGet($this->url, 302);
        $this->assertRedirect('/admin?redirect=' . urlencode($this->url));

        $this->assertPost($this->url . '/edit', [], 302);
        $this->assertRedirect('/admin?redirect=' . urlencode($this->url . '/edit'));
    }

    public function testAsPlayer(): void
    {
        $this->withAuthPlayer();

        $this->assertGet($this->url, 403);

        $this->assertPost($this->url . '/edit', [], 403);
    }

    public function testAsReadOnly(): void
    {
        $this->withAuthReadOnly();

        $this->assertGet($this->url);

        $this->assertPost($this->url . '/edit', [], 302);
        $this->assertRedirect($this->url);
    }
}
