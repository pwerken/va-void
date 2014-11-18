<?php
namespace App\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use App\Model\Table\BelievesTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\BelievesTable Test Case
 */
class BelievesTableTest extends TestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = [
		'Believes' => 'app.believes',
		'Characters' => 'app.characters',
		'Players' => 'app.players',
		'Factions' => 'app.factions',
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
		$config = TableRegistry::exists('Believes') ? [] : ['className' => 'App\Model\Table\BelievesTable'];

		$this->Believes = TableRegistry::get('Believes', $config);

	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Believes);

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
