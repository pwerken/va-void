<?php
namespace App\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use App\Model\Table\AttributesTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\AttributesTable Test Case
 */
class AttributesTableTest extends TestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = [
		'Attributes' => 'app.attributes',
		'Items' => 'app.items',
		'Characters' => 'app.characters',
		'Players' => 'app.players',
		'Factions' => 'app.factions',
		'Believes' => 'app.believes',
		'Groups' => 'app.groups',
		'Worlds' => 'app.worlds',
		'Conditions' => 'app.conditions',
		'CharactersConditions' => 'app.characters_conditions',
		'Powers' => 'app.powers',
		'CharactersPowers' => 'app.characters_powers',
		'Skills' => 'app.skills',
		'Manatypes' => 'app.manatypes',
		'CharactersSkills' => 'app.characters_skills',
		'Spells' => 'app.spells',
		'CharactersSpells' => 'app.characters_spells',
		'AttributesItems' => 'app.attributes_items'
	];

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$config = TableRegistry::exists('Attributes') ? [] : ['className' => 'App\Model\Table\AttributesTable'];

		$this->Attributes = TableRegistry::get('Attributes', $config);

	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Attributes);

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
