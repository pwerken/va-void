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
		'app.skills',
		'app.manatypes',
		'app.characters',
		'app.players',
		'app.factions',
		'app.believes',
		'app.groups',
		'app.worlds',
		'app.items',
		'app.attributes',
		'app.attributes_items',
		'app.conditions',
		'app.characters_conditions',
		'app.powers',
		'app.characters_powers',
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
