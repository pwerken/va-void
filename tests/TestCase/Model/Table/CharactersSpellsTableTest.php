<?php
namespace App\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use App\Model\Table\CharactersSpellsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\CharactersSpellsTable Test Case
 */
class CharactersSpellsTableTest extends TestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = [
		'CharactersSpells' => 'app.characters_spells',
		'Characters' => 'app.characters',
		'Players' => 'app.players',
		'Factions' => 'app.factions',
		'Believes' => 'app.believes',
		'Groups' => 'app.groups',
		'Worlds' => 'app.worlds',
		'Items' => 'app.items',
		'Attributes' => 'app.attributes',
		'AttributesItems' => 'app.attributes_items',
		'Conditions' => 'app.conditions',
		'CharactersConditions' => 'app.characters_conditions',
		'Powers' => 'app.powers',
		'CharactersPowers' => 'app.characters_powers',
		'Skills' => 'app.skills',
		'Manatypes' => 'app.manatypes',
		'CharactersSkills' => 'app.characters_skills',
		'Spells' => 'app.spells'
	];

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$config = TableRegistry::exists('CharactersSpells') ? [] : ['className' => 'App\Model\Table\CharactersSpellsTable'];

		$this->CharactersSpells = TableRegistry::get('CharactersSpells', $config);

	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->CharactersSpells);

		parent::tearDown();
	}

/**
 * Test initialize method
 *
 * @return void
 */
	public function testInitialize() {
		$this->markTestIncomplete('Not implemented yet.');
	}

/**
 * Test validationDefault method
 *
 * @return void
 */
	public function testValidationDefault() {
		$this->markTestIncomplete('Not implemented yet.');
	}

}
