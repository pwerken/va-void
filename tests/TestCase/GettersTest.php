<?php
namespace App\Test\TestCase\Controller;

use App\Test\TestSuite\AuthIntegrationTestCase;

class GettersTest
	extends AuthIntegrationTestCase
{

	public $fixtures =
		[ 'app.attributes'
		, 'app.attributesItems'
		, 'app.believes'
		, 'app.characters'
		, 'app.charactersConditions'
		, 'app.charactersPowers'
		, 'app.charactersSkills'
		, 'app.charactersSpells'
		, 'app.conditions'
		, 'app.events'
		, 'app.factions'
		, 'app.groups'
		, 'app.items'
		, 'app.lammies'
		, 'app.manatypes'
		, 'app.players'
		, 'app.powers'
		, 'app.skills'
		, 'app.spells'
		, 'app.teachings'
		, 'app.worlds'
		];

	public function testRoot()
	{
		$this->withoutAuth();
		$this->assertGet('/');
	}

	public function testPlayers()
	{
		$this->withoutAuth();
		$this->assertGet('/players', 403);
		$this->assertGet('/players/1', 403);
		$this->assertGet('/players/1/characters', 403);
		$this->assertGet('/players/2', 403);
		$this->assertGet('/players/2/characters', 403);
		$this->assertGet('/players/99', 403);
		$this->assertGet('/players/99/characters', 403);

		$this->withAuthPlayer();
		$this->assertGet('/players');
		$this->assertGet('/players/1');
		$this->assertGet('/players/1/characters');
		$this->assertGet('/players/2', 403);
		$this->assertGet('/players/2/characters', 403);
		$this->assertGet('/players/99', 403);
		$this->assertGet('/players/99/characters', 403);

		$this->withAuthReadOnly();
		$this->assertGet('/players');
		$this->assertGet('/players/1');
		$this->assertGet('/players/1/characters');
		$this->assertGet('/players/2');
		$this->assertGet('/players/2/characters');
		$this->assertGet('/players/99', 404);
		$this->assertGet('/players/99/characters', 404);
	}

	public function testCharacters()
	{
		$this->withoutAuth();
		$this->assertGet('/characters', 403);
		$this->assertGet('/characters/1', 403);
		$this->assertGet('/characters/1/1', 403);
		$this->assertGet('/characters/1/1/items', 403);
		$this->assertGet('/characters/1/1/conditions', 403);
		$this->assertGet('/characters/1/1/conditions/1', 403);
		$this->assertGet('/characters/1/1/conditions/2', 403);
		$this->assertGet('/characters/1/1/powers', 403);
		$this->assertGet('/characters/1/1/powers/1', 403);
		$this->assertGet('/characters/1/1/powers/2', 403);
		$this->assertGet('/characters/1/1/skills', 403);
		$this->assertGet('/characters/1/1/skills/1', 403);
		$this->assertGet('/characters/1/1/spells', 403);
		$this->assertGet('/characters/1/1/spells/1', 403);
		$this->assertGet('/characters/1/1/students', 403);
		$this->assertGet('/characters/1/1/teacher', 403);
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
		$this->assertGet('/characters/1/2/spells', 404);
		$this->assertGet('/characters/1/2/spells/1', 404);
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
		$this->assertGet('/characters/2/1/spells', 403);
		$this->assertGet('/characters/2/1/spells/1', 403);
		$this->assertGet('/characters/2/1/students', 403);
		$this->assertGet('/characters/2/1/teacher', 403);
#FIXME: 404 -> 403
		$this->assertGet('/characters/2/2', 404);
		$this->assertGet('/characters/2/2/items', 404);
		$this->assertGet('/characters/2/2/conditions', 404);
		$this->assertGet('/characters/2/2/conditions/1', 404);
		$this->assertGet('/characters/2/2/powers', 404);
		$this->assertGet('/characters/2/2/powers/1', 404);
		$this->assertGet('/characters/2/2/skills', 404);
		$this->assertGet('/characters/2/2/skills/1', 404);
		$this->assertGet('/characters/2/2/spells', 404);
		$this->assertGet('/characters/2/2/spells/1', 404);
		$this->assertGet('/characters/2/2/students', 404);
		$this->assertGet('/characters/2/2/teacher', 404);
		$this->assertGet('/characters/99', 403);
		$this->assertGet('/characters/99/1', 404);
		$this->assertGet('/characters/99/1/items', 404);
		$this->assertGet('/characters/99/1/conditions', 404);
		$this->assertGet('/characters/99/1/conditions/1', 404);
		$this->assertGet('/characters/99/1/powers', 404);
		$this->assertGet('/characters/99/1/powers/1', 404);
		$this->assertGet('/characters/99/1/skills', 404);
		$this->assertGet('/characters/99/1/skills/1', 404);
		$this->assertGet('/characters/99/1/spells', 404);
		$this->assertGet('/characters/99/1/spells/1', 404);
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
		$this->assertGet('/characters/1/1/spells');
		$this->assertGet('/characters/1/1/spells/1');
		$this->assertGet('/characters/1/1/students');
		$this->assertGet('/characters/1/1/teacher'); // FIXME fixture
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
		$this->assertGet('/characters/1/2/spells', 404);
		$this->assertGet('/characters/1/2/spells/1', 404);
		$this->assertGet('/characters/1/2/students', 404);
		$this->assertGet('/characters/1/2/teacher', 404);
		$this->assertGet('/characters/2', 403);
		$this->assertGet('/characters/2/1', 403);
		$this->assertGet('/characters/2/1/items', 403);
		$this->assertGet('/characters/2/1/conditions', 200); // FIXME 403
		$this->assertGet('/characters/2/1/conditions/1', 403);
		$this->assertGet('/characters/2/1/powers', 200); // FIXME: 403
		$this->assertGet('/characters/2/1/powers/1', 403);
		$this->assertGet('/characters/2/1/skills', 403);
		$this->assertGet('/characters/2/1/skills/1', 403);
		$this->assertGet('/characters/2/1/spells', 403);
		$this->assertGet('/characters/2/1/spells/1', 403);
		$this->assertGet('/characters/2/1/students', 403);
		$this->assertGet('/characters/2/1/teacher', 403);
		$this->assertGet('/characters/99', 403);
#FIXME: 404 -> 403
		$this->assertGet('/characters/99/1', 404);
		$this->assertGet('/characters/99/1/items', 404);
		$this->assertGet('/characters/99/1/conditions', 404);
		$this->assertGet('/characters/99/1/conditions/1', 404);
		$this->assertGet('/characters/99/1/powers', 404);
		$this->assertGet('/characters/99/1/powers/1', 404);
		$this->assertGet('/characters/99/1/skills', 404);
		$this->assertGet('/characters/99/1/skills/1', 404);
		$this->assertGet('/characters/99/1/spells', 404);
		$this->assertGet('/characters/99/1/spells/1', 404);
		$this->assertGet('/characters/99/1/students', 404);
		$this->assertGet('/characters/99/1/teacher', 404);
	}

	public function testConditions()
	{
		$this->withoutAuth();
		$this->assertGet('/conditions', 403);
		$this->assertGet('/conditions/1', 403);
		$this->assertGet('/conditions/1/characters', 403);
		$this->assertGet('/conditions/2', 403);
		$this->assertGet('/conditions/2/characters', 403);
		$this->assertGet('/conditions/99', 403);
		$this->assertGet('/conditions/99/characters', 403);

		$this->withAuthPlayer();
		$this->assertGet('/conditions');
		$this->assertGet('/conditions/1');
		$this->assertGet('/conditions/1/characters');
		$this->assertGet('/conditions/2', 403);
		$this->assertGet('/conditions/2/characters', 403);
		$this->assertGet('/conditions/99', 403);
		$this->assertGet('/conditions/99/characters', 403);

		$this->withAuthReadOnly();
		$this->assertGet('/conditions');
		$this->assertGet('/conditions/1');
		$this->assertGet('/conditions/1/characters');
		$this->assertGet('/conditions/2');
		$this->assertGet('/conditions/2/characters');
		$this->assertGet('/conditions/99', 404);
		$this->assertGet('/conditions/99/characters', 404);
	}

	public function testPowers()
	{
		$this->withoutAuth();
		$this->assertGet('/powers', 403);
		$this->assertGet('/powers/1', 403);
		$this->assertGet('/powers/1/characters', 403);
		$this->assertGet('/powers/2', 403);
		$this->assertGet('/powers/2/characters', 403);
		$this->assertGet('/powers/99', 403);
		$this->assertGet('/powers/99/characters', 403);

		$this->withAuthPlayer();
		$this->assertGet('/powers');
		$this->assertGet('/powers/1');
		$this->assertGet('/powers/1/characters');
		$this->assertGet('/powers/2', 403);
		$this->assertGet('/powers/2/characters', 403);
		$this->assertGet('/powers/99', 403);
		$this->assertGet('/powers/99/characters', 403);

		$this->withAuthReadOnly();
		$this->assertGet('/powers');
		$this->assertGet('/powers/1');
		$this->assertGet('/powers/1/characters');
		$this->assertGet('/powers/2');
		$this->assertGet('/powers/2/characters');
		$this->assertGet('/powers/99', 404);
		$this->assertGet('/powers/99/characters', 404);
	}

	public function testSkills()
	{
		$this->withoutAuth();
		$this->assertGet('/skills', 403);
		$this->assertGet('/skills/1', 403);
		$this->assertGet('/skills/1/characters', 403);
		$this->assertGet('/skills/99', 403);
		$this->assertGet('/skills/99/characters', 403);

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

	public function testSpells()
	{
		$this->withoutAuth();
		$this->assertGet('/spells', 403);
		$this->assertGet('/spells/1', 403);
		$this->assertGet('/spells/1/characters', 403);
		$this->assertGet('/spells/99', 403);
		$this->assertGet('/spells/99/characters', 403);

		$this->withAuthPlayer();
		$this->assertGet('/spells');
		$this->assertGet('/spells/1');
		$this->assertGet('/spells/1/characters', 403);
		$this->assertGet('/spells/99', 404);
		$this->assertGet('/spells/99/characters', 403);

		$this->withAuthReadOnly();
		$this->assertGet('/spells');
		$this->assertGet('/spells/1');
		$this->assertGet('/spells/1/characters');
		$this->assertGet('/spells/99', 404);
		$this->assertGet('/spells/99/characters', 404);
	}

	public function testItems()
	{
		$this->withoutAuth();
		$this->assertGet('/items', 403);
		$this->assertGet('/items/1', 403);
		$this->assertGet('/items/1/attributes', 403);
		$this->assertGet('/items/1/attributes/1', 403);
		$this->assertGet('/items/99', 403);
		$this->assertGet('/items/99/attributes', 403);

		$this->withAuthPlayer();
		$this->assertGet('/items');
		$this->assertGet('/items/1');
		$this->assertGet('/items/1/attributes', 403);
		$this->assertGet('/items/1/attributes/1', 403);
		$this->assertGet('/items/1/attributes/2', 403);
		$this->assertGet('/items/2', 403);
		$this->assertGet('/items/2/attributes/1', 403);
		$this->assertGet('/items/2/attributes/2', 403);
		$this->assertGet('/items/99', 403);
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
		$this->assertGet('/items/99/attrbutes', 404);
		$this->assertGet('/items/99/attributes', 404);
		$this->assertGet('/items/99/attributes/1', 404);
		$this->assertGet('/items/99/attributes/2', 404);
	}

	public function testAttributes()
	{
		$this->withoutAuth();
		$this->assertGet('/attributes', 403);
		$this->assertGet('/attributes/1', 403);
		$this->assertGet('/attributes/1/items', 403);
		$this->assertGet('/attributes/99', 403);
		$this->assertGet('/attributes/99/items', 403);

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
		$this->assertGet('/believes', 403);
		$this->assertGet('/believes/1', 403);
		$this->assertGet('/believes/1/characters', 403);
		$this->assertGet('/believes/99', 403);
		$this->assertGet('/believes/99/characters', 403);

		$this->withAuthPlayer();
		$this->assertGet('/believes');
		$this->assertGet('/believes/1');
		$this->assertGet('/believes/1/characters', 403);
		$this->assertGet('/believes/99', 404);
		$this->assertGet('/believes/99/characters', 403);

		$this->withAuthReadOnly();
		$this->assertGet('/believes');
		$this->assertGet('/believes/1');
		$this->assertGet('/believes/1/characters');
		$this->assertGet('/believes/99', 404);
		$this->assertGet('/believes/99/characters', 404);
	}

	public function testFactions()
	{
		$this->withoutAuth();
		$this->assertGet('/factions', 403);
		$this->assertGet('/factions/1', 403);
		$this->assertGet('/factions/1/characters', 403);
		$this->assertGet('/factions/99', 403);
		$this->assertGet('/factions/99/characters', 403);

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
		$this->assertGet('/groups', 403);
		$this->assertGet('/groups/1', 403);
		$this->assertGet('/groups/1/characters', 403);
		$this->assertGet('/groups/99', 403);
		$this->assertGet('/groups/99/characters', 403);

		$this->withAuthPlayer();
		$this->assertGet('/groups');
		$this->assertGet('/groups/1');
		$this->assertGet('/groups/1/characters', 403);
		$this->assertGet('/groups/99', 404);
		$this->assertGet('/groups/99/characters', 403);

		$this->withAuthReadOnly();
		$this->assertGet('/groups');
		$this->assertGet('/groups/1');
		$this->assertGet('/groups/1/characters');
		$this->assertGet('/groups/99', 404);
		$this->assertGet('/groups/99/characters', 404);
	}

	public function testWorlds()
	{
		$this->withoutAuth();
		$this->assertGet('/worlds', 403);
		$this->assertGet('/worlds/1', 403);
		$this->assertGet('/worlds/1/characters', 403);
		$this->assertGet('/worlds/99', 403);
		$this->assertGet('/worlds/99/characters', 403);

		$this->withAuthPlayer();
		$this->assertGet('/worlds');
		$this->assertGet('/worlds/1');
		$this->assertGet('/worlds/1/characters', 403);
		$this->assertGet('/worlds/99', 404);
		$this->assertGet('/worlds/99/characters', 403);

		$this->withAuthReadOnly();
		$this->assertGet('/worlds');
		$this->assertGet('/worlds/1');
		$this->assertGet('/worlds/1/characters');
		$this->assertGet('/worlds/99', 404);
		$this->assertGet('/worlds/99/characters', 404);
	}

	public function testEvents()
	{
		$this->withoutAuth();
		$this->assertGet('/events', 403);
		$this->assertGet('/events/1', 403);
		$this->assertGet('/events/99', 403);

		$this->withAuthPlayer();
		$this->assertGet('/events');
		$this->assertGet('/events/1');
		$this->assertGet('/events/99', 404);
	}

	public function testLammies()
	{
		$this->withoutAuth();
		$this->assertGet('/lammies', 403);
		$this->assertGet('/lammies/1', 403);
		$this->assertGet('/lammies/99', 403);
		$this->assertGet('/lammies/queue', 403);

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
