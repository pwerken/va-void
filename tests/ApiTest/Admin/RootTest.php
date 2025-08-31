<?php
declare(strict_types=1);

namespace App\Test\ApiTest\Admin;

use App\Test\TestSuite\AuthIntegrationTestCase;

class RootTest extends AuthIntegrationTestCase
{
    public function testNotLoggedIn(): void
    {
        $this->withoutAuth();
        $this->assertGet('/admin');
        $this->assertGet('/admin?redirect=%2Flocation');
    }

    public function testLogin(): void
    {
        $this->withoutAuth();

        $credentials = [
            'plin' => 1,
            'password' => 'password',
        ];

        $this->assertPost('/admin', []);
        $this->assertFlashMessage('Invalid username or password');

        $this->assertPost('/admin', $credentials);
        $this->assertFlashMessage('Logged in as Player One');

        $this->assertPost('/admin?redirect=%2Flocation', $credentials, 302);
        $this->assertRedirect('/location');
        $this->assertFlashMessage('Logged in as Player One');
    }

    public function testAsPlayer(): void
    {
        $this->withAuthPlayer();
        $this->assertGet('/admin');

        $this->assertGet('/admin?redirect=%2Flocation', 302);
        $this->assertRedirect('/location');

        $this->assertPost('/admin', []);
        $this->assertFlashMessage('Logged in as Player One');
    }
}
