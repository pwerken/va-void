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
		'app.factions',
		'app.characters',
		'app.players',
		'app.believes',
		'app.groups',
		'app.worlds',
		'app.items',
		'app.conditions',
		'app.characters_conditions',
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
