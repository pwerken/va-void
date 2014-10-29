<?php
namespace App\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use App\Model\Table\WorldsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\WorldsTable Test Case
 */
class WorldsTableTest extends TestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = [
		'app.worlds',
		'app.characters',
		'app.players',
		'app.factions',
		'app.believes',
		'app.groups',
		'app.items',
		'app.attributes',
		'app.attributes_items',
		'app.conditions',
		'app.characters_conditions',
		'app.powers',
		'app.characters_powers',
		'app.skills',
		'app.manatypes',
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
		$config = TableRegistry::exists('Worlds') ? [] : ['className' => 'App\Model\Table\WorldsTable'];
		$this->Worlds = TableRegistry::get('Worlds', $config);
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Worlds);

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
