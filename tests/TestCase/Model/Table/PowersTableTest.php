<?php
namespace App\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use App\Model\Table\PowersTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\PowersTable Test Case
 */
class PowersTableTest extends TestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = [
		'Powers' => 'app.powers',
		'Characters' => 'app.characters',
		'CharactersPowers' => 'app.characters_powers'
	];

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$config = TableRegistry::exists('Powers') ? [] : ['className' => 'App\Model\Table\PowersTable'];

		$this->Powers = TableRegistry::get('Powers', $config);

	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Powers);

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
