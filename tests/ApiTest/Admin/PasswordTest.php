<?php
declare(strict_types=1);

namespace App\Test\ApiTest\Admin;

use App\Test\TestSuite\AuthIntegrationTestCase;

class PasswordTest extends AuthIntegrationTestCase
{
    protected string $url = '/admin/password';

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

        $this->assertGet($this->url);

        $this->assertPost($this->url . '/edit', [], 302);
        $this->assertRedirect($this->url);
    }
}
