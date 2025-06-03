<?php
declare(strict_types=1);

namespace App\Test\ApiTest;

use App\Test\TestSuite\AuthIntegrationTestCase;

class RootTest extends AuthIntegrationTestCase
{
    public function testIndex(): void
    {
        $this->withoutAuth();
        $this->assertGet('/', 302);
        $this->assertRedirectContains('/admin');
    }

    public function testCORS(): void
    {
        $this->withoutAuth();
        $this->assertOptions('/');
        $this->assertOptions('/doesnotexist');
        $this->assertOptions('/players');
    }

    public function testDoesNotExist(): void
    {
        $this->withoutAuth();
        $this->assertGet('/doesNotExist', 404);
        $this->assertPut('/doesNotExist', [], 404);
        $this->assertPost('/doesNotExist', [], 404);
        $this->assertDelete('/doesNotExist', 404);
    }
}
