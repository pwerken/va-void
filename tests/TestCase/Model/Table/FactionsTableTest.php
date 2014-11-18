<?php
namespace App\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use App\Model\Table\FactionsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\FactionsTable Test Case
 */
class FactionsTableTest extends TestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = [
		'Factions' => 'app.factions',
		'Characters' => 'app.characters',
		'Players' => 'app.players',
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
		$config = TableRegistry::exists('Factions') ? [] : ['className' => 'App\Model\Table\FactionsTable'];

		$this->Factions = TableRegistry::get('Factions', $config);

	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Factions);

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
