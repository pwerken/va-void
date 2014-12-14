<?php
namespace App\Test\TestCase\View\Helper;

use App\View\Helper\SnippetHelper;
use Cake\TestSuite\TestCase;
use Cake\View\View;

/**
 * App\View\Helper\SnippetHelper Test Case
 */
class SnippetHelperTest extends TestCase {

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$view = new View();
		$this->Snippet = new SnippetHelper($view);
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Snippet);

		parent::tearDown();
	}

/**
 * Test initial setup
 *
 * @return void
 */
	public function testInitialization() {
		$this->markTestIncomplete('Not implemented yet.');
	}

}
