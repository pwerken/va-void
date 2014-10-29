<?php
namespace App\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use App\Model\Table\CharactersConditionsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\CharactersConditionsTable Test Case
 */
class CharactersConditionsTableTest extends TestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = [
		'app.characters_conditions',
		'app.characters',
		'app.players',
		'app.factions',
		'app.believes',
		'app.groups',
		'app.worlds',
		'app.items',
		'app.conditions',
		'app.powers',
		'app.characters_powers',
		'app.skills',
		'app.characters_skills',
		'app.spells',
		'app.characters_spells'
	];

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$config = TableRegistry::exists('CharactersConditions') ? [] : ['className' => 'App\Model\Table\CharactersConditionsTable'];
		$this->CharactersConditions = TableRegistry::get('CharactersConditions', $config);
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->CharactersConditions);

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
