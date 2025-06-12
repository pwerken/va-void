<?php
declare(strict_types=1);

namespace App\Test\ApiTest;

use App\Test\TestSuite\AuthIntegrationTestCase;

class GettersTest extends AuthIntegrationTestCase
{
    public function testSkills(): void
    {
        $this->withoutAuth();
        $this->assertGet('/skills', 401);
        $this->assertGet('/skills/1', 401);
        $this->assertGet('/skills/1/characters', 401);
        $this->assertGet('/skills/99', 401);
        $this->assertGet('/skills/99/characters', 401);

        $this->withAuthPlayer();
        $this->assertGet('/skills');
        $this->assertGet('/skills/1');
        $this->assertGet('/skills/1/characters', 403);
        $this->assertGet('/skills/99', 404);
        $this->assertGet('/skills/99/characters', 403);

        $this->withAuthReadOnly();
        $this->assertGet('/skills');
        $this->assertGet('/skills/1');
        $this->assertGet('/skills/1/characters');
        $this->assertGet('/skills/99', 404);
        $this->assertGet('/skills/99/characters', 404);
    }

    public function testManatypes(): void
    {
        $this->withoutAuth();
        $this->assertGet('/manatypes', 401);
        $this->assertGet('/manatypes/1', 401);
        $this->assertGet('/manatypes/99', 401);

        $this->withAuthPlayer();
        $this->assertGet('/manatypes');
        $this->assertGet('/manatypes/1');
        $this->assertGet('/manatypes/99', 404);
    }

    public function testBelieves(): void
    {
        $this->withoutAuth();
        $this->assertGet('/believes', 401);

        $this->withAuthPlayer();
        $this->assertGet('/believes');
    }

    public function testFactions(): void
    {
        $this->withoutAuth();
        $this->assertGet('/factions', 401);
        $this->assertGet('/factions/1', 401);
        $this->assertGet('/factions/1/characters', 401);
        $this->assertGet('/factions/99', 401);
        $this->assertGet('/factions/99/characters', 401);

        $this->withAuthPlayer();
        $this->assertGet('/factions');
        $this->assertGet('/factions/1');
        $this->assertGet('/factions/1/characters', 403);
        $this->assertGet('/factions/99', 404);
        $this->assertGet('/factions/99/characters', 403);

        $this->withAuthReadOnly();
        $this->assertGet('/factions');
        $this->assertGet('/factions/1');
        $this->assertGet('/factions/1/characters');
        $this->assertGet('/factions/99', 404);
        $this->assertGet('/factions/99/characters', 404);
    }

    public function testGroups(): void
    {
        $this->withoutAuth();
        $this->assertGet('/groups', 401);

        $this->withAuthPlayer();
        $this->assertGet('/groups');
    }

    public function testWorlds(): void
    {
        $this->withoutAuth();
        $this->assertGet('/worlds', 401);

        $this->withAuthPlayer();
        $this->assertGet('/worlds');
    }

    public function testEvents(): void
    {
        $this->withoutAuth();
        $this->assertGet('/events', 401);
        $this->assertGet('/events/1', 401);
        $this->assertGet('/events/99', 401);

        $this->withAuthPlayer();
        $this->assertGet('/events');
        $this->assertGet('/events/1');
        $this->assertGet('/events/99', 404);
    }
}
