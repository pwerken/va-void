<?php
namespace App\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use App\Model\Table\SpellsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\SpellsTable Test Case
 */
class SpellsTableTest extends TestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = [
		'Spells' => 'app.spells',
		'Characters' => 'app.characters',
		'CharactersSpells' => 'app.characters_spells'
	];

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$config = TableRegistry::exists('Spells') ? [] : ['className' => 'App\Model\Table\SpellsTable'];

		$this->Spells = TableRegistry::get('Spells', $config);

	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Spells);

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
