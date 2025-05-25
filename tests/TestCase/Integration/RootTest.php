<?php
declare(strict_types=1);

namespace App\Test\TestCase\Integration;

use App\Test\TestSuite\AuthIntegrationTestCase;

class RootTest extends AuthIntegrationTestCase
{
    public function testIndex()
    {
        $this->withoutAuth();
        $this->assertGet('/', 302);
        $this->assertRedirectContains('/admin');
    }

    public function testCORS()
    {
        $this->withoutAuth();
        $this->assertOptions('/');
        $this->assertOptions('/doesnotexist');
        $this->assertOptions('/players');
    }
}
