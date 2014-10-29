<?php
namespace App\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use App\Model\Table\ItemsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ItemsTable Test Case
 */
class ItemsTableTest extends TestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = [
		'app.items',
		'app.characters',
		'app.players',
		'app.factions',
		'app.believes',
		'app.groups',
		'app.worlds',
		'app.conditions',
		'app.characters_conditions',
		'app.powers',
		'app.characters_powers',
		'app.skills',
		'app.characters_skills',
		'app.spells',
		'app.characters_spells',
		'app.attributes',
		'app.attributes_items'
	];

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$config = TableRegistry::exists('Items') ? [] : ['className' => 'App\Model\Table\ItemsTable'];
		$this->Items = TableRegistry::get('Items', $config);
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Items);

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
