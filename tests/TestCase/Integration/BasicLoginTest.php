<?php
namespace App\Test\TestCase\Integration;

use App\Test\TestSuite\AuthIntegrationTestCase;

class BasicLoginTest
	extends AuthIntegrationTestCase
{
	public function testWithoutAuth()
	{
		$this->withoutAuth();
		$this->assertNull($this->token, 'JWT Token set?');
	}

	public function testWithAuthPlayer()
	{
		$this->withAuthPlayer();
	}

	public function testWithAuthReadOnly()
	{
		$this->withAuthReadOnly();
	}

	public function testWithAuthReferee()
	{
		$this->withAuthReferee();
	}

	public function testWithAuthInfobalie()
	{
		$this->withAuthInfobalie();
	}

	public function testWithAuthSuper()
	{
		$this->withAuthSuper();
	}
}
