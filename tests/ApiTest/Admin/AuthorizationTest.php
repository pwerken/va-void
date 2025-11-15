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
        $this->assertRedirect('/admin');
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

        $this->assertPost($this->url . '/edit', ['plin' => 1, 'role' => 'Read-only'], 302);
        $this->assertRedirect($this->url);
        $this->assertFlashMessage('Not authorized to change roles');
    }

    public function testAsReferee(): void
    {
        $this->withAuthReferee();

        $input = ['plin' => 1, 'role' => 'role'];

        $this->assertGet($this->url);

        $this->assertPost($this->url . '/edit', [], 302);
        $this->assertRedirect($this->url);
        $this->assertFlashMessage('Player#0 not found');

        $this->assertPost($this->url . '/edit', $input, 302);
        $this->assertRedirect($this->url);
        $this->assertFlashMessage('Invalid authorization role provided');

        $input['role'] = 'Referee';
        $this->assertPost($this->url . '/edit', $input, 302);
        $this->assertRedirect($this->url);
        $this->assertFlashMessage('Player#1 now has `Referee` authorization');

        $this->assertPost($this->url . '/edit', $input, 302);
        $this->assertRedirect($this->url);
        $this->assertFlashMessage('Player#1 already has `Referee` authorization');

        $input['role'] = 'Infobalie';
        $this->assertPost($this->url . '/edit', $input, 302);
        $this->assertRedirect($this->url);
        $this->assertFlashMessage('Cannot promote user above your own authorization');
    }
}
