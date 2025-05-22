<?php
declare(strict_types=1);

namespace App\Test\TestCase\Integration;

use App\Test\TestSuite\AuthIntegrationTestCase;

class GettersTest extends AuthIntegrationTestCase
{
    public array $fixtures = [
        'app.Attributes',
        'app.AttributesItems',
        'app.Characters',
        'app.CharactersConditions',
        'app.CharactersPowers',
        'app.CharactersSkills',
        'app.Conditions',
        'app.Events',
        'app.Factions',
        'app.Items',
        'app.Lammies',
        'app.Manatypes',
        'app.Players',
        'app.Powers',
        'app.Skills',
        'app.Teachings',
    ];

    public function testRoot()
    {
        $this->withoutAuth();
        $this->get('/');
        $this->assertRedirectContains('/admin');
    }

    public function testCharacters()
    {
        $this->withoutAuth();
        $this->assertGet('/characters', 401);
        $this->assertGet('/characters/1', 401);
        $this->assertGet('/characters/1/1', 401);
        $this->assertGet('/characters/1/1/items', 401);
        $this->assertGet('/characters/1/1/conditions', 401);
        $this->assertGet('/characters/1/1/conditions/1', 401);
        $this->assertGet('/characters/1/1/conditions/2', 401);
        $this->assertGet('/characters/1/1/powers', 401);
        $this->assertGet('/characters/1/1/powers/1', 401);
        $this->assertGet('/characters/1/1/powers/2', 401);
        $this->assertGet('/characters/1/1/skills', 401);
        $this->assertGet('/characters/1/1/skills/1', 401);
        $this->assertGet('/characters/1/1/students', 401);
        $this->assertGet('/characters/1/1/teacher', 401);
        $this->assertGet('/characters/1/2', 404);
        $this->assertGet('/characters/1/2/items', 404);
        $this->assertGet('/characters/1/2/conditions', 404);
        $this->assertGet('/characters/1/2/conditions/1', 404);
        $this->assertGet('/characters/1/2/conditions/2', 404);
        $this->assertGet('/characters/1/2/powers', 404);
        $this->assertGet('/characters/1/2/powers/1', 404);
        $this->assertGet('/characters/1/2/powers/2', 404);
        $this->assertGet('/characters/1/2/skills', 404);
        $this->assertGet('/characters/1/2/skills/1', 404);
        $this->assertGet('/characters/1/2/students', 404);
        $this->assertGet('/characters/1/2/teacher', 404);
        $this->assertGet('/characters/2', 401);
        $this->assertGet('/characters/2/1', 401);
        $this->assertGet('/characters/2/1/items', 401);
        $this->assertGet('/characters/2/1/conditions', 401);
        $this->assertGet('/characters/2/1/conditions/1', 401);
        $this->assertGet('/characters/2/1/powers', 401);
        $this->assertGet('/characters/2/1/powers/1', 401);
        $this->assertGet('/characters/2/1/skills', 401);
        $this->assertGet('/characters/2/1/skills/1', 401);
        $this->assertGet('/characters/2/1/students', 401);
        $this->assertGet('/characters/2/1/teacher', 401);
        $this->assertGet('/characters/2/2', 404);
        $this->assertGet('/characters/2/2/items', 404);
        $this->assertGet('/characters/2/2/conditions', 404);
        $this->assertGet('/characters/2/2/conditions/1', 404);
        $this->assertGet('/characters/2/2/powers', 404);
        $this->assertGet('/characters/2/2/powers/1', 404);
        $this->assertGet('/characters/2/2/skills', 404);
        $this->assertGet('/characters/2/2/skills/1', 404);
        $this->assertGet('/characters/2/2/students', 404);
        $this->assertGet('/characters/2/2/teacher', 404);
        $this->assertGet('/characters/99', 401);
        $this->assertGet('/characters/99/1', 404);
        $this->assertGet('/characters/99/1/items', 404);
        $this->assertGet('/characters/99/1/conditions', 404);
        $this->assertGet('/characters/99/1/conditions/1', 404);
        $this->assertGet('/characters/99/1/powers', 404);
        $this->assertGet('/characters/99/1/powers/1', 404);
        $this->assertGet('/characters/99/1/skills', 404);
        $this->assertGet('/characters/99/1/skills/1', 404);
        $this->assertGet('/characters/99/1/students', 404);
        $this->assertGet('/characters/99/1/teacher', 404);

        $this->withAuthPlayer();
        $this->assertGet('/characters');
        $this->assertGet('/characters/1');
        $this->assertGet('/characters/1/1');
        $this->assertGet('/characters/1/1/items');
        $this->assertGet('/characters/1/1/conditions');
        $this->assertGet('/characters/1/1/conditions/1');
        $this->assertGet('/characters/1/1/conditions/2', 404);
        $this->assertGet('/characters/1/1/powers');
        $this->assertGet('/characters/1/1/powers/1');
        $this->assertGet('/characters/1/1/powers/2', 404);
        $this->assertGet('/characters/1/1/skills');
        $this->assertGet('/characters/1/1/skills/1');
        $this->assertGet('/characters/1/1/skills/2', 404);
#       $this->assertGet('/characters/1/1/students');
#       $this->assertGet('/characters/1/1/teacher'); // FIXME fixture
        $this->assertGet('/characters/1/2', 404);
        $this->assertGet('/characters/1/2/items', 404);
        $this->assertGet('/characters/1/2/conditions', 404);
        $this->assertGet('/characters/1/2/conditions/1', 404);
        $this->assertGet('/characters/1/2/conditions/2', 404);
        $this->assertGet('/characters/1/2/powers', 404);
        $this->assertGet('/characters/1/2/powers/1', 404);
        $this->assertGet('/characters/1/2/powers/2', 404);
        $this->assertGet('/characters/1/2/skills', 404);
        $this->assertGet('/characters/1/2/skills/1', 404);
        $this->assertGet('/characters/1/2/skills/2', 404);
        $this->assertGet('/characters/1/2/students', 404);
        $this->assertGet('/characters/1/2/teacher', 404);
        $this->assertGet('/characters/2', 403);
        $this->assertGet('/characters/2/1', 403);
        $this->assertGet('/characters/2/1/items', 403);
        $this->assertGet('/characters/2/1/conditions', 403);
        $this->assertGet('/characters/2/1/conditions/1', 403);
        $this->assertGet('/characters/2/1/powers', 403);
        $this->assertGet('/characters/2/1/powers/1', 403);
        $this->assertGet('/characters/2/1/skills', 403);
        $this->assertGet('/characters/2/1/skills/1', 403);
#       $this->assertGet('/characters/2/1/students', 403);
#       $this->assertGet('/characters/2/1/teacher', 403);
        $this->assertGet('/characters/99', 404);
        $this->assertGet('/characters/99/1', 404);
        $this->assertGet('/characters/99/1/items', 404);
        $this->assertGet('/characters/99/1/conditions', 404);
        $this->assertGet('/characters/99/1/conditions/1', 404);
        $this->assertGet('/characters/99/1/powers', 404);
        $this->assertGet('/characters/99/1/powers/1', 404);
        $this->assertGet('/characters/99/1/skills', 404);
        $this->assertGet('/characters/99/1/skills/1', 404);
#       $this->assertGet('/characters/99/1/students', 404);
#       $this->assertGet('/characters/99/1/teacher', 404);
    }

    public function testSkills()
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

    public function testManatypes()
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

    public function testItems()
    {
        $this->withoutAuth();
        $this->assertGet('/items', 401);
        $this->assertGet('/items/1', 401);
        $this->assertGet('/items/1/attributes', 401);
        $this->assertGet('/items/1/attributes/1', 401);
        $this->assertGet('/items/99', 401);
        $this->assertGet('/items/99/attributes', 401);

        $this->withAuthPlayer();
        $this->assertGet('/items');
        $this->assertGet('/items/1');
        $this->assertGet('/items/1/attributes', 403);
        $this->assertGet('/items/1/attributes/1', 403);
        $this->assertGet('/items/1/attributes/2', 403);
        $this->assertGet('/items/2', 403);
        $this->assertGet('/items/2/attributes/1', 403);
        $this->assertGet('/items/2/attributes/2', 403);
        $this->assertGet('/items/99', 404);
        $this->assertGet('/items/99/attributes', 403);
        $this->assertGet('/items/99/attributes/1', 403);
        $this->assertGet('/items/99/attributes/2', 403);

        $this->withAuthReadOnly();
        $this->assertGet('/items');
        $this->assertGet('/items/1');
        $this->assertGet('/items/1/attributes');
        $this->assertGet('/items/1/attributes/1');
        $this->assertGet('/items/1/attributes/2', 404);
        $this->assertGet('/items/2');
        $this->assertGet('/items/2/attributes');
        $this->assertGet('/items/2/attributes/1', 404);
        $this->assertGet('/items/2/attributes/2');
        $this->assertGet('/items/99', 404);
        $this->assertGet('/items/99/attributes', 404);
        $this->assertGet('/items/99/attributes/1', 404);
        $this->assertGet('/items/99/attributes/2', 404);
    }

    public function testAttributes()
    {
        $this->withoutAuth();
        $this->assertGet('/attributes', 401);
        $this->assertGet('/attributes/1', 401);
        $this->assertGet('/attributes/1/items', 401);
        $this->assertGet('/attributes/99', 401);
        $this->assertGet('/attributes/99/items', 401);

        $this->withAuthPlayer();
        $this->assertGet('/attributes', 403);
        $this->assertGet('/attributes/1', 403);
        $this->assertGet('/attributes/1/items', 403);
        $this->assertGet('/attributes/99', 403);
        $this->assertGet('/attributes/99/items', 403);

        $this->withAuthReadOnly();
        $this->assertGet('/attributes');
        $this->assertGet('/attributes/1');
        $this->assertGet('/attributes/1/items');
        $this->assertGet('/attributes/99', 404);
        $this->assertGet('/attributes/99/items', 404);
    }

    public function testBelieves()
    {
        $this->withoutAuth();
        $this->assertGet('/believes', 401);
        $this->assertGet('/believes/1', 404);
        $this->assertGet('/believes/1/characters', 404);

        $this->withAuthPlayer();
        $this->assertGet('/believes');
        $this->assertGet('/believes/1', 404);
        $this->assertGet('/believes/1/characters', 404);
    }

    public function testFactions()
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

    public function testGroups()
    {
        $this->withoutAuth();
        $this->assertGet('/groups', 401);
        $this->assertGet('/groups/1', 404);
        $this->assertGet('/groups/1/characters', 404);

        $this->withAuthPlayer();
        $this->assertGet('/groups');
        $this->assertGet('/groups/1', 404);
        $this->assertGet('/groups/1/characters', 404);
    }

    public function testWorlds()
    {
        $this->withoutAuth();
        $this->assertGet('/worlds', 401);
        $this->assertGet('/worlds/1', 404);
        $this->assertGet('/worlds/1/characters', 404);

        $this->withAuthPlayer();
        $this->assertGet('/worlds');
        $this->assertGet('/worlds/1', 404);
        $this->assertGet('/worlds/1/characters', 404);
    }

    public function testEvents()
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

    public function testLammies()
    {
        $this->withoutAuth();
        $this->assertGet('/lammies', 401);
        $this->assertGet('/lammies/99', 401);
        $this->assertGet('/lammies/queue', 401);

        $this->withAuthPlayer();
        $this->assertGet('/lammies', 403);
        $this->assertGet('/lammies/1', 403);
        $this->assertGet('/lammies/99', 403);
        $this->assertGet('/lammies/queue', 403);

        $this->withAuthReadOnly();
        $this->assertGet('/lammies');
        $this->assertGet('/lammies/1');
        $this->assertGet('/lammies/99', 404);
        $this->assertGet('/lammies/queue', 403);

        $this->withAuthReferee();
        $this->assertGet('/lammies');
        $this->assertGet('/lammies/1');
        $this->assertGet('/lammies/99', 404);
        $this->assertGet('/lammies/queue', 403);

        $this->withAuthInfobalie();
        $this->assertGet('/lammies');
        $this->assertGet('/lammies/1');
        $this->assertGet('/lammies/99', 404);
        $this->assertGet('/lammies/queue');
    }
}
