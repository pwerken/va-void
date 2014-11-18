<?php
namespace App\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use App\Model\Table\ConditionsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ConditionsTable Test Case
 */
class ConditionsTableTest extends TestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = [
		'Conditions' => 'app.conditions',
		'Characters' => 'app.characters',
		'CharactersConditions' => 'app.characters_conditions'
	];

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$config = TableRegistry::exists('Conditions') ? [] : ['className' => 'App\Model\Table\ConditionsTable'];

		$this->Conditions = TableRegistry::get('Conditions', $config);

	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Conditions);

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
