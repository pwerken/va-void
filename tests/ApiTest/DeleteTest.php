<?php
declare(strict_types=1);

namespace App\Test\ApiTest;

use App\Test\TestSuite\AuthIntegrationTestCase;

class DeleteTest extends AuthIntegrationTestCase
{
    public function testDeleteBySuper(): void
    {
        $this->withAuthSuper();
        // validation errors
        $this->assertDelete('/conditions/2', 422);
        $this->assertDelete('/powers/2', 422);
        $this->assertDelete('/items/2', 422);
        $this->assertDelete('/characters/2/1', 422);
        $this->assertDelete('/players/2', 422);

        // ok
        $this->assertDelete('/characters/2/1/conditions/1');
        $this->assertDelete('/characters/2/1/conditions/2');
        $this->assertDelete('/characters/2/1/powers/1');
        $this->assertDelete('/characters/2/1/powers/2');
        $this->assertPut('/items/2', ['plin' => null, 'chin' => null]);
        $this->assertDelete('/items/2');
        $this->assertDelete('/characters/2/1');
        $this->assertDelete('/players/2');
    }
}
