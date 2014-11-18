<?php
namespace App\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use App\Model\Table\CharactersPowersTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\CharactersPowersTable Test Case
 */
class CharactersPowersTableTest extends TestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = [
		'CharactersPowers' => 'app.characters_powers',
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
		'Skills' => 'app.skills',
		'Manatypes' => 'app.manatypes',
		'CharactersSkills' => 'app.characters_skills',
		'Spells' => 'app.spells',
		'CharactersSpells' => 'app.characters_spells'
	];

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$config = TableRegistry::exists('CharactersPowers') ? [] : ['className' => 'App\Model\Table\CharactersPowersTable'];

		$this->CharactersPowers = TableRegistry::get('CharactersPowers', $config);

	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->CharactersPowers);

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
