<?php
namespace App\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use App\Model\Table\SkillsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\SkillsTable Test Case
 */
class SkillsTableTest extends TestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = [
		'Skills' => 'app.skills',
		'Manatypes' => 'app.manatypes',
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
		$config = TableRegistry::exists('Skills') ? [] : ['className' => 'App\Model\Table\SkillsTable'];

		$this->Skills = TableRegistry::get('Skills', $config);

	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Skills);

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
