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
        $this->assertRedirect('/admin');
    }

    public function testAsPlayer(): void
    {
        $this->withAuthPlayer();

        $this->assertGet($this->url);

        $input = ['plin' => 99, 'password' => 'test'];
        $this->assertPost($this->url . '/edit', $input, 302);
        $this->assertRedirect($this->url);
        $this->assertFlashMessage('Player#99 not found');

        $input['plin'] = 1;
        $this->assertPost($this->url . '/edit', $input, 302);
        $this->assertRedirect($this->url);
        $this->assertFlashMessage('Player#1 password set');

        $input['password'] = '';
        $this->assertPost($this->url . '/edit', $input, 302);
        $this->assertRedirect($this->url);
        $this->assertFlashMessage('Player#1 password removed');

        $input['plin'] = 2;
        $this->assertPost($this->url . '/edit', $input, 302);
        $this->assertRedirect($this->url);
        $this->assertFlashMessage('Not authorized to change password');
    }
}
